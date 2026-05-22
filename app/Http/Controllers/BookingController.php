<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller;

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
        $rooms = Room::with('bookings.user')->get()->sortBy('floor');
        return view('create_booking', [
            'rooms' => $rooms,
            'departmentOptions' => $this->departmentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after:now',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        /** @var Carbon $startTime */
        $timezone = config('app.timezone');
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, $timezone);
        /** @var Carbon $endTime */
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, $timezone);
        $roomId = (int) $request->room_id;

        // Check duration (max 24 hours)
        $durationHours = $endTime->diffInHours($startTime);
        if ($durationHours > 24) {
            return back()->withErrors(['end_time' => 'Durasi booking maksimal 24 jam']);
        }

        // Check conflict/overlap
        $conflict = Booking::where('room_id', $roomId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['time' => 'Ruang sudah ada booking pada waktu tersebut']);
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['error' => 'User tidak ditemukan']);
        }
        
        Booking::create([
            'user_id' => $user->id,
            'room_id' => $roomId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'title' => $validated['title'] ?? 'Meeting',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat');
    }

    public function index(): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        $bookings = $user ? $user->bookings()->with('room')->orderBy('start_time', 'desc')->get() : collect();
        return view('list_bookings', ['bookings' => $bookings]);
    }

    public function edit(Booking $booking): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $booking->user_id !== $user->id) {
            abort(403);
        }

        $rooms = Room::all()->sortBy('floor');
        return view('edit_booking', [
            'booking' => $booking,
            'rooms' => $rooms,
            'departmentOptions' => $this->departmentOptions(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $booking->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        /** @var Carbon $startTime */
        $timezone = config('app.timezone');
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, $timezone);
        /** @var Carbon $endTime */
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, $timezone);
        $roomId = (int) $request->room_id;
        $bookingId = (int) $booking->id;

        // Check duration (max 24 hours)
        $durationHours = $endTime->diffInHours($startTime);
        if ($durationHours > 24) {
            return back()->withErrors(['end_time' => 'Durasi booking maksimal 24 jam']);
        }

        // Check conflict/overlap (exclude current booking)
        $conflict = Booking::where('room_id', $roomId)
            ->where('id', '!=', $bookingId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['time' => 'Ruang sudah ada booking pada waktu tersebut']);
        }

        $booking->update([
            'room_id' => $roomId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'title' => $validated['title'] ?? $booking->title,
            'description' => $validated['description'] ?? $booking->description,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diubah');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * @return array<int, string>
     */
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
