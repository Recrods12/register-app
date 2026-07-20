<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Booking Management Controller
 */
class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';

        // Admin dapat membooking atas nama user lain, jadi perlu daftar user
        $users = $isAdmin ? User::all() : collect();

        $rooms = Room::with('bookings.user')->get()->sortBy('floor');
        return view('create_booking', [
            'rooms' => $rooms,
            'users' => $users,
            'departmentOptions' => $this->departmentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';

        // User biasa tidak boleh booking waktu yang sudah lewat.
        // Admin diperbolehkan (mis. mencatat/back-date rapat yang sudah selesai).
        $startRule = $isAdmin
            ? 'required|date_format:Y-m-d\TH:i'
            : 'required|date_format:Y-m-d\TH:i|after:now';

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => $startRule,
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            ...($isAdmin ? ['user_id' => 'nullable|exists:users,id'] : []),
        ]);

        /** @var Carbon $startTime */
        $timezone = config('app.timezone');
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, $timezone);
        /** @var Carbon $endTime */
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, $timezone);
        $roomId = (int) $request->room_id;

        // Check duration (max 24 hours)
        if ($startTime->diffInMinutes($endTime) > 24 * 60) {
            return back()->withErrors(['end_time' => 'Durasi booking maksimal 24 jam']);
        }

        // Check conflict/overlap (boundary/adjacent times are allowed)
        if ($this->hasConflict($roomId, $startTime, $endTime)) {
            return back()->withErrors(['time' => 'Ruang sudah ada booking pada waktu tersebut']);
        }

        if (!$user) {
            return back()->withErrors(['error' => 'User tidak ditemukan']);
        }

        $booking = Booking::create([
            'user_id' => ($isAdmin && $request->filled('user_id')) ? (int) $request->user_id : $user->id,
            'room_id' => $roomId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'title' => $validated['title'] ?? 'Meeting',
            'description' => $validated['description'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'booking_created',
            'message' => 'Membuat booking ruang ' . $roomId . ' pada ' . $startTime->format('d M Y H:i'),
            'changes' => ['room_id' => $roomId, 'start_time' => $startTime->toDateTimeString(), 'end_time' => $endTime->toDateTimeString()],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat');
    }

    public function index(Request $request): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';

        // Default filter values used by views (export links, filters UI)
        $filters = [
            'start_date' => $request->query('start_date', ''),
            'end_date' => $request->query('end_date', ''),
            'user_id' => $request->query('user_id', ''),
            'search' => $request->query('search', ''),
        ];

        if ($isAdmin) {
            $query = Booking::with(['room', 'user'])->orderBy('start_time', 'desc');

            if ($filters['start_date'] && $filters['end_date']) {
                try {
                    $start = Carbon::createFromFormat('Y-m-d', $filters['start_date'])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $filters['end_date'])->endOfDay();
                    $query->whereBetween('start_time', [$start, $end]);
                } catch (\Throwable $e) {
                    // ignore invalid date formats
                }
            }

            if ($filters['user_id']) {
                $query->where('user_id', (int) $filters['user_id']);
            }

            if ($filters['search']) {
                $s = $filters['search'];
                $query->where(function ($q) use ($s) {
                    $q->where('title', 'like', "%{$s}%")
                        ->orWhere('description', 'like', "%{$s}%");
                });
            }

            $bookings = $query->get();
        } else {
            $bookings = $user ? $user->bookings()->with('room')->orderBy('start_time', 'desc')->get() : collect();
        }

        // Provide users list for admin filter dropdown
        $users = $isAdmin ? User::all() : collect();

        return view('list_bookings', compact('bookings', 'filters', 'isAdmin', 'users'));
    }

    public function edit(Booking $booking): View
    {
        if (!$this->canManageBooking($booking)) {
            abort(403);
        }

        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';

        // Admin dapat mengubah pemesan booking orang lain
        $users = $isAdmin ? User::all() : collect();

        $rooms = Room::all()->sortBy('floor');
        return view('edit_booking', [
            'booking' => $booking,
            'rooms' => $rooms,
            'users' => $users,
            'departmentOptions' => $this->departmentOptions(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        if (!$this->canManageBooking($booking)) {
            abort(403);
        }

        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            ...($isAdmin ? ['user_id' => 'nullable|exists:users,id'] : []),
        ]);

        /** @var Carbon $startTime */
        $timezone = config('app.timezone');
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, $timezone);
        /** @var Carbon $endTime */
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, $timezone);
        $roomId = (int) $request->room_id;
        $bookingId = (int) $booking->id;

        // Waktu mulai tidak boleh diubah ke masa lalu (booking yang sedang
        // berjalan boleh diedit asalkan waktunya tidak diubah ke belakang)
        if ($startTime->lt(now())
            && $startTime->format('Y-m-d H:i') !== $booking->start_time->format('Y-m-d H:i')) {
            return back()->withErrors(['start_time' => 'Waktu mulai tidak boleh diubah ke masa lalu']);
        }

        // Check duration (max 24 hours)
        if ($startTime->diffInMinutes($endTime) > 24 * 60) {
            return back()->withErrors(['end_time' => 'Durasi booking maksimal 24 jam']);
        }

        // Check conflict/overlap (exclude current booking; boundary times allowed)
        if ($this->hasConflict($roomId, $startTime, $endTime, $bookingId)) {
            return back()->withErrors(['time' => 'Ruang sudah ada booking pada waktu tersebut']);
        }

        $booking->update([
            'user_id' => ($isAdmin && $request->filled('user_id')) ? (int) $request->user_id : $booking->user_id,
            'room_id' => $roomId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'title' => $validated['title'] ?? $booking->title,
            'description' => $validated['description'] ?? $booking->description,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'booking_updated',
            'message' => 'Mengubah booking ruang ' . $roomId . ' pada ' . $startTime->format('d M Y H:i'),
            'changes' => ['room_id' => $roomId, 'start_time' => $startTime->toDateTimeString(), 'end_time' => $endTime->toDateTimeString()],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diubah');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        if (!$this->canManageBooking($booking)) {
            abort(403);
        }
        ActivityLog::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'booking_deleted',
            'message' => 'Membatalkan booking ruang ' . $booking->room_id . ' pada ' . $booking->start_time->format('d M Y H:i'),
            'changes' => ['room_id' => $booking->room_id, 'start_time' => $booking->start_time->toDateTimeString()],
        ]);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan');
    }

    public function showActivity(Booking $booking): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';
        if (!$isAdmin && $booking->user_id !== $user?->id) {
            abort(403);
        }

        $maxUploadMb = 10; // guide for client-side validation
        return view('upload_activity', compact('booking', 'maxUploadMb'));
    }

    public function storeActivity(Request $request, Booking $booking): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';
        if (!$isAdmin && $booking->user_id !== $user?->id) {
            abort(403);
        }

        $validated = $request->validate([
            'activity_photo' => 'required|image|max:10240', // 10MB
        ]);

        $file = $request->file('activity_photo');
        if ($file) {
            // delete old file if exists
            if ($booking->activity_photo_path) {
                Storage::disk('public')->delete($booking->activity_photo_path);
            }

            $path = $file->store('activity_photos', 'public');
            $booking->activity_photo_path = $path;
            $booking->activity_note = $booking->description ?: null;
            $booking->save();
        }

        return redirect()->route('bookings.activity', $booking)->with('success', 'Foto kegiatan berhasil disimpan');
    }

    public function deleteActivity(Booking $booking): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isAdmin = $user?->role === 'admin';
        if (!$isAdmin && $booking->user_id !== $user?->id) {
            abort(403);
        }

        if ($booking->activity_photo_path) {
            Storage::disk('public')->delete($booking->activity_photo_path);
            $booking->activity_photo_path = null;
            $booking->activity_note = null;
            $booking->save();
        }

        return redirect()->route('bookings.activity', $booking)->with('success', 'Foto kegiatan dihapus');
    }

    /**
     * Otorisasi: pemilik booking atau admin diperbolehkan mengelola booking.
     */
    private function canManageBooking(Booking $booking): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return $user->role === 'admin' || $booking->user_id === $user->id;
    }

    /**
     * Cek apakah ada booking lain yang bentrok dengan rentang waktu tersebut
     * pada ruang yang sama. Dua booking dianggap bentrok jika intervalnya
     * overlap (bukan sekadar bersebelahan di batas waktu).
     */
    private function hasConflict(int $roomId, Carbon $startTime, Carbon $endTime, ?int $excludeId = null): bool
    {
        return Booking::where('room_id', $roomId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->exists();
    }
    private function departmentOptions(): array
    {
        return [
            'Bidang Hubungan Industrial',
            'Bidang Sekretariat',
            'Bidang Energi',
            'Bidang Pengantar Kerja',
            'Bidang Pengawasan',
            'Bidang Produktivitas dan Pelatihan',
        ];
    }
}
