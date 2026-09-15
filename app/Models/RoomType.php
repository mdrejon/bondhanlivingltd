<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'name', 'slug', 'short_desc', 'description',
        'price', 'price_usd', 'price_unit',
        'discount_type_bdt', 'discount_value_bdt',
        'discount_type_usd', 'discount_value_usd',
        'offer_expires_at',
        'check_in_time', 'check_out_time',
        'max_adults', 'max_children', 'bed_type',
        'amenities', 'features', 'room_rules', 'gallery_images',
        'feature_image',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'rating', 'is_featured', 'sort_order', 'is_active',
    ];

    protected $appends = ['discounted_price_bdt', 'discounted_price_usd'];

    protected $casts = [
        'price'               => 'decimal:2',
        'price_usd'           => 'decimal:2',
        'discount_value_bdt'  => 'decimal:2',
        'discount_value_usd'  => 'decimal:2',
        'offer_expires_at'    => 'datetime',
        'rating'              => 'decimal:1',
        'amenities'           => 'array',
        'features'            => 'array',
        'room_rules'          => 'array',
        'gallery_images'      => 'array',
        'is_featured'         => 'boolean',
        'is_active'           => 'boolean',
        'sort_order'          => 'integer',
        'max_adults'          => 'integer',
        'max_children'        => 'integer',
    ];

    public function roomAmenities(): BelongsToMany
    {
        return $this->belongsToMany(RoomAmenity::class, 'room_type_amenity');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function getDiscountedPriceBdtAttribute(): ?float
    {
        if (!$this->discount_value_bdt || !$this->discount_type_bdt || !$this->isOfferActive()) {
            return null;
        }
        $discounted = $this->discount_type_bdt === 'percentage'
            ? $this->price - ($this->price * $this->discount_value_bdt / 100)
            : $this->price - $this->discount_value_bdt;

        return round(max($discounted, 0), 2);
    }

    public function getDiscountedPriceUsdAttribute(): ?float
    {
        if (!$this->price_usd || !$this->discount_value_usd || !$this->discount_type_usd || !$this->isOfferActive()) {
            return null;
        }
        $discounted = $this->discount_type_usd === 'percentage'
            ? $this->price_usd - ($this->price_usd * $this->discount_value_usd / 100)
            : $this->price_usd - $this->discount_value_usd;

        return round(max($discounted, 0), 2);
    }

    private function isOfferActive(): bool
    {
        return !$this->offer_expires_at || now()->lt($this->offer_expires_at);
    }

    /**
     * The per-night rate to actually charge in the given currency — the discounted
     * rate when an active offer applies, otherwise the plain listed rate. Returns
     * null for USD when this room type has no USD price configured at all.
     */
    public function effectivePrice(string $currency = 'BDT'): ?float
    {
        if (strtoupper($currency) === 'USD') {
            if (!$this->price_usd) {
                return null;
            }
            return $this->discounted_price_usd ?? (float) $this->price_usd;
        }

        return $this->discounted_price_bdt ?? (float) $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
