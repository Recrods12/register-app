<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string|null $title
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Room $room
 */
class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id', 'room_id', 'start_time', 'end_time', 'title', 'description'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive(Builder $query, Carbon|string|null $moment = null): Builder
    {
        $moment = $moment ? Carbon::parse($moment) : now();

        return $query
            ->where('start_time', '<=', $moment)
            ->where('end_time', '>', $moment);
    }

    public function scopeUpcoming(Builder $query, Carbon|string|null $moment = null): Builder
    {
        $moment = $moment ? Carbon::parse($moment) : now();

        return $query->where('start_time', '>', $moment);
    }

    public function isActive(): bool
    {
        return $this->start_time <= now() && $this->end_time > now();
    }

    public function isEnded(): bool
    {
        return $this->end_time <= now();
    }

    public function isUpcoming(): bool
    {
        return $this->start_time > now();
    }
}
