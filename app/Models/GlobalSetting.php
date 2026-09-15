<?php

namespace App\Models;

use App\Support\CurrentHotel;
use Illuminate\Database\Eloquent\Model;

/**
 * Per-hotel website/SMTP/content config (About copy, header/footer, mail settings,
 * etc.), one row per (hotel_id, key). Deliberately does NOT use the shared
 * BelongsToHotel trait: that trait scopes to CurrentHotel::visibleHotelIds() — a
 * *set* of hotels a jurisdiction-style account may see (right for Room/Customer/
 * Inquiry reads across several hotels). A setting always belongs to exactly one
 * hotel's site, so this scopes to CurrentHotel::homeId() — a single concrete hotel —
 * instead, which also resolves correctly for a Super Admin (whose visibleHotelIds()
 * is unrestricted/null, but whose homeId() still resolves to one real hotel).
 *
 * Every call site — the 13 Admin\WebsiteSettings\* controllers' raw
 * whereIn('key', ...)->pluck() queries included — automatically becomes correctly
 * hotel-scoped via this global scope, with no controller changes needed.
 */
class GlobalSetting extends Model
{
    protected $fillable = ['hotel_id', 'key', 'value'];

    /** Get a setting value by key (current hotel), with optional default. */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /** Set (upsert) a single setting for the current hotel. */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'hotel_id' => 0]);
    }

    /** Bulk-upsert an associative array of key => value pairs for the current hotel. */
    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value);
        }
    }

    /** Return all of the current hotel's settings as a flat key=>value array. */
    public static function allAsArray(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }
}
