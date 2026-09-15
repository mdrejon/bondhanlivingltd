<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'review', 'name', 'role', 'avatar', 'rating', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'rating'     => 'float',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
