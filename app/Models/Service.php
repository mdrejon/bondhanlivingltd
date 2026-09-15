<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'title', 'slug', 'icon_svg', 'image', 'short_desc',
        'description', 'benefits_title', 'benefits_text',
        'gallery_image_1', 'gallery_image_2',
        'features', 'faqs',
        'btn_text', 'btn_url',
        'is_featured', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'features'    => 'array',
        'faqs'        => 'array',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)->where('is_featured', true)
            ->orderBy('sort_order')->orderBy('id');
    }
}
