<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'page', 'badge', 'title', 'description', 'image', 'image_alt', 'items', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'items'      => 'array',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page)->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id');
    }
}
