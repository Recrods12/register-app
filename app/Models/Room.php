<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['floor', 'name', 'description'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the active booking (currently being used)
     * @return Booking|null
     */
    public function getActiveBooking()
    {
        return $this->bookings()
            ->active()
            ->orderBy('start_time')
            ->with('user')
            ->first();
    }

    /**
     * Get the next upcoming booking today
     * @return Booking|null
     */
    public function getNextBooking()
    {
        $now = now();
        $endOfToday = $now->copy()->endOfDay();

        return $this->bookings()
            ->upcoming($now)
            ->where('start_time', '<=', $endOfToday)
            ->orderBy('start_time')
            ->with('user')
            ->first();
    }

    /**
     * Get current status: 'booked' or 'available'
     * @return string
     */
    public function getCurrentStatus()
    {
        return $this->getActiveBooking() ? 'booked' : 'available';
    }
}
