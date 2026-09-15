<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFollowUp extends Model
{
    protected $table = 'booking_followups';

    protected $fillable = [
        'booking_id',
        'title',
        'description',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(RoomBooking::class, 'booking_id');
    }
}
