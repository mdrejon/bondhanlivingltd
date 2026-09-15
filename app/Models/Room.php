<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'room_type_id', 'room_number', 'room_name',
        'floor', 'building', 'image', 'status', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('room_number');
    }
}
