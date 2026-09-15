<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomBooking extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'booking_reference', 'customer_id',
        'check_in_date', 'check_out_date',
        'adults', 'children', 'total_nights',
        'total_amount', 'currency', 'advance_payment', 'discount_amount',
        'payment_method', 'special_requests',
        'booking_status', 'notes', 'booked_by', 'source',
    ];

    protected $casts = [
        'check_in_date'    => 'date',
        'check_out_date'   => 'date',
        'total_amount'     => 'decimal:2',
        'advance_payment'  => 'decimal:2',
        'discount_amount'  => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The room lines that make up this reservation — one row per physical room requested.
     * A booking with "2 Deluxe Couple + 3 Triple Deluxe" has 5 rows here.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function bookedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(BookingFollowUp::class, 'booking_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BookingLog::class, 'booking_id');
    }
}
