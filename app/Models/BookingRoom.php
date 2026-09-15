<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRoom extends Model
{
    protected $fillable = [
        'room_booking_id', 'room_type_id', 'room_id',
        'adults', 'children', 'nights',
        'price_per_night', 'line_total',
        'status', 'actual_check_in_at', 'actual_check_out_at',
    ];

    protected $casts = [
        'price_per_night'     => 'decimal:2',
        'line_total'          => 'decimal:2',
        'actual_check_in_at'  => 'datetime',
        'actual_check_out_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(RoomBooking::class, 'room_booking_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Lines belonging to a booking whose header dates overlap the given range.
     * Dates live on the parent booking (all lines in a booking share one stay).
     */
    public function scopeOverlapping(Builder $query, string $checkIn, string $checkOut): Builder
    {
        return $query->whereHas('booking', function (Builder $q) use ($checkIn, $checkOut) {
            $q->where('check_in_date', '<', $checkOut)
                ->where('check_out_date', '>', $checkIn);
        });
    }

    public function scopeWithBookingStatus(Builder $query, array $statuses): Builder
    {
        return $query->whereHas('booking', fn (Builder $q) => $q->whereIn('booking_status', $statuses));
    }

    /**
     * Excludes lines cancelled individually, even if the parent booking as a whole is active.
     */
    public function scopeActiveLine(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }
}
