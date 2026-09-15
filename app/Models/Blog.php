<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'category_id', 'title', 'slug', 'excerpt', 'content',
        'feature_image', 'og_image', 'tags',
        'author_name', 'author_bio', 'author_avatar',
        'status', 'published_at', 'is_featured',
        'meta_title', 'meta_description', 'meta_keywords',
        'view_count', 'sort_order',
    ];

    protected $casts = [
        'tags'         => 'array',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'sort_order'   => 'integer',
        'view_count'   => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', true)->whereNull('parent_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(fn($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function getTagsListAttribute(): string
    {
        return implode(', ', $this->tags ?? []);
    }
}
