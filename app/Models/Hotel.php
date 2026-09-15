<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'template', 'trade_license_no', 'bin_no', 'tin_no',
        'owner_name', 'owner_nid', 'mobile', 'email', 'address',
        'district_id', 'upazila_id', 'police_station_id',
        'category', 'total_rooms', 'logo', 'is_primary_site', 'status',
    ];

    protected $casts = [
        'is_primary_site' => 'boolean',
        'total_rooms'     => 'integer',
    ];

    private static ?self $primarySiteCache = null;
    private static bool $primarySiteResolved = false;

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function policeStation(): BelongsTo
    {
        return $this->belongsTo(PoliceStation::class);
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * The one hotel the public marketing site currently represents. Used by
     * App\Support\CurrentHotel to scope tenant queries for unauthenticated (public
     * website / console / queue) requests. Cached per request/process — this is a
     * plain web app (no Octane), so a static cache is safe and avoids re-querying on
     * every RoomType/Room/Customer/Inquiry lookup on a public page.
     */
    public static function primarySite(): ?self
    {
        if (! self::$primarySiteResolved) {
            self::$primarySiteCache = static::where('is_primary_site', true)->first()
                ?? static::orderBy('id')->first();
            self::$primarySiteResolved = true;
        }

        return self::$primarySiteCache;
    }
}
