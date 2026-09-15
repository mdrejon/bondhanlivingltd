<?php

namespace App\Support;

use App\Models\Hotel;

/**
 * Resolves which hotel(s) a tenant-scoped query/write should apply to. Backs
 * App\Models\Concerns\BelongsToHotel — see that trait for how it's used.
 */
class CurrentHotel
{
    /**
     * Hotel ids to filter reads by. Returns null only to mean "unrestricted" (super
     * admin, who must see every hotel) — every other case resolves to a concrete
     * (possibly empty) list, so a misconfigured or jurisdiction-less account fails
     * closed (whereIn on an empty array matches zero rows) instead of silently
     * falling back to unrestricted access.
     *
     * A single hotel-role user gets a one-element list; a government (district/
     * upazila/police_station scope_type) user gets every hotel in their jurisdiction
     * — see App\Support\HotelAccess for how that set is computed.
     */
    public static function visibleHotelIds(): ?array
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isSuperAdmin()) {
                return null;
            }

            return HotelAccess::visibleHotelIds($user);
        }

        // Public website / artisan / queue context. A request under /hotel/{slug}
        // has already had its hotel resolved by ResolvePublicHotel middleware — use
        // that when present. Otherwise fall back to the one hotel the root-level
        // public site represents (see Hotel::primarySite()).
        $hotel = PublicHotelContext::get() ?? Hotel::primarySite();

        return [$hotel?->id ?? 0];
    }

    /**
     * The hotel_id to stamp onto a newly-created record. Unlike id(), this never
     * means "unrestricted" — even a super admin's own actions need a concrete home
     * hotel, which is their own hotel_id (populated for every existing account).
     */
    public static function homeId(): ?int
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Super Admin "acting as" a specific hotel (see ActingHotelController) —
            // lets them manage a non-primary hotel's website/SMTP/content without a
            // dedicated per-hotel account. Only honored for super admins: a regular
            // hotel-role user's own hotel_id is never overridable by a session value.
            if ($user->isSuperAdmin() && session()->has('acting_hotel_id')) {
                return session('acting_hotel_id');
            }

            return $user->hotel_id ?? Hotel::primarySite()?->id;
        }

        return PublicHotelContext::get()?->id ?? Hotel::primarySite()?->id;
    }
}
