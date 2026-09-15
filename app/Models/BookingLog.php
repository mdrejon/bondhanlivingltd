<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingLog extends Model
{
    protected $table = 'booking_logs';

    protected $fillable = [
        'booking_id',
        'action',
        'description',
        'old_value',
        'new_value',
        'performed_by',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(RoomBooking::class, 'booking_id');
    }
}
