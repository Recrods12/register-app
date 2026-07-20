<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->role === 'admin') {
                return $this->adminDashboard($request, $user);
            }

            return $this->dashboardBooking($user);
        }

        return $this->rooms();
    }

    public function rooms(): View
    {
        return $this->buildRoomsView();
    }

    private function buildRoomsView(): View
    {
        $rooms = Room::with('bookings.user')->get()->sortBy('floor');

        $roomsWithStatus = $rooms->map(function (Room $room): array {
            return [
                'id' => $room->id,
                'floor' => $room->floor,
                'name' => $room->name,
                'status' => $room->getCurrentStatus(),
                'activeBooking' => $room->getActiveBooking(),
                'nextBooking' => $room->getNextBooking(),
            ];
        });

        return view('index', [
            'rooms' => $roomsWithStatus,
            'activeBookings' => Booking::with('user', 'room')->active()->orderBy('start_time', 'desc')->get(),
            'bookingSchedules' => Booking::with('user', 'room')
                ->where('end_time', '>', now())
                ->orderBy('start_time')
                ->limit(8)
                ->get(),
        ]);
    }

    private function dashboardBooking(User $user): View
    {
        $bookings = $user->bookings()->with('room')->orderBy('start_time', 'desc')->get();
        $rooms = Room::all()->sortBy('floor');

        return view('dashboard_booking', [
            'isAdmin' => false,
            'totalBookings' => $bookings->count(),
            'activeBookings' => $bookings->filter(fn (Booking $booking) => $booking->isActive())->count(),
            'upcomingBookings' => $bookings->filter(fn (Booking $booking) => $booking->isUpcoming())->count(),
            'availableRooms' => $rooms->filter(fn (Room $room) => $room->getCurrentStatus() === 'available')->count(),
            'availableRoomsList' => $rooms->filter(fn (Room $room) => $room->getCurrentStatus() === 'available')
                ->map(fn (Room $room) => ['id' => $room->id, 'floor' => $room->floor, 'name' => $room->name])
                ->values(),
            'activeBookingsList' => $bookings->filter(fn (Booking $booking) => $booking->isActive()),
            'upcomingBookingsList' => $bookings->filter(fn (Booking $booking) => $booking->isUpcoming())->take(5),
            'reportBookings' => collect(),
            'users' => collect(),
            'filters' => [
                'start_date' => null,
                'end_date' => null,
                'user_id' => null,
                'search' => null,
            ],
        ]);
    }

    private function adminDashboard(Request $request, User $user): View
    {
        $allBookings = Booking::with(['room', 'user'])->orderBy('start_time', 'desc')->get();
        $rooms = Room::all()->sortBy('floor');

        $startDate = $request->string('start_date')->value() ?: now()->toDateString();
        $endDate = $request->string('end_date')->value() ?: $startDate;
        $selectedUserId = $request->string('user_id')->value();
        $search = trim($request->string('search')->value());

        $reportBookings = Booking::with(['room', 'user'])
            ->when($startDate, fn ($query) => $query->where('start_time', '>=', Carbon::parse($startDate, config('app.timezone'))->startOfDay()))
            ->when($endDate, fn ($query) => $query->where('start_time', '<=', Carbon::parse($endDate, config('app.timezone'))->endOfDay()))
            ->when($selectedUserId, fn ($query) => $query->where('user_id', (int) $selectedUserId))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('room', fn ($roomQuery) => $roomQuery->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('user', function ($userQuery) use ($search): void {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('start_time')
            ->get();

        return view('dashboard_booking', [
            'isAdmin' => true,
            'totalBookings' => $allBookings->count(),
            'activeBookings' => $allBookings->filter(fn (Booking $booking) => $booking->isActive())->count(),
            'upcomingBookings' => $allBookings->filter(fn (Booking $booking) => $booking->isUpcoming())->count(),
            'availableRooms' => $rooms->filter(fn (Room $room) => $room->getCurrentStatus() === 'available')->count(),
            'availableRoomsList' => $rooms->filter(fn (Room $room) => $room->getCurrentStatus() === 'available')
                ->map(fn (Room $room) => ['id' => $room->id, 'floor' => $room->floor, 'name' => $room->name])
                ->values(),
            'activeBookingsList' => $allBookings->filter(fn (Booking $booking) => $booking->isActive())->take(8),
            'upcomingBookingsList' => $allBookings->filter(fn (Booking $booking) => $booking->isUpcoming())->take(8),
            'reportBookings' => $reportBookings,
            'users' => User::orderBy('name')->get(),
            'activityLogs' => ActivityLog::with(['user', 'booking.room'])->latest()->limit(3)->get(),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_id' => $selectedUserId,
                'search' => $search,
            ],
        ]);
    }
}
