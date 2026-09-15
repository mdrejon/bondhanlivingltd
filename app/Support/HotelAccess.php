<?php

namespace App\Support;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Computes which hotels a non-super-admin user is allowed to see. Two mechanisms,
 * checked in this order:
 *
 * 1. Explicit assignment (`hotel_user_assignments`) — if present, the visible set is
 *    exactly those hotels (narrows or extends beyond the jurisdiction match below).
 * 2. Jurisdiction auto-match — the user's district_id/upazila_id/police_station_id
 *    (whichever applies to their role's scope_type) matched against the same column
 *    on `hotels`. A DC user with district_id = 5 sees every hotel where
 *    hotels.district_id = 5, automatically.
 *
 * Super admin is handled by the caller (App\Support\CurrentHotel) — this class only
 * ever deals with non-super-admin users, so a `hotel` scope_type user with no
 * hotel_id, or a `district` scope_type user with no district_id, correctly resolves
 * to an empty set (fail closed) rather than every hotel.
 */
class HotelAccess
{
    /** @return int[] hotel ids the user can see. Empty means none. */
    public static function visibleHotelIds(User $user): array
    {
        $assigned = DB::table('hotel_user_assignments')
            ->where('user_id', $user->id)
            ->pluck('hotel_id');

        if ($assigned->isNotEmpty()) {
            return $assigned->all();
        }

        return match ($user->role?->scope_type) {
            'district'       => $user->district_id
                ? Hotel::where('district_id', $user->district_id)->pluck('id')->all()
                : [],
            'upazila'        => $user->upazila_id
                ? Hotel::where('upazila_id', $user->upazila_id)->pluck('id')->all()
                : [],
            'police_station' => $user->police_station_id
                ? Hotel::where('police_station_id', $user->police_station_id)->pluck('id')->all()
                : [],
            'hotel'          => [ $user->hotel_id ?: (Hotel::primarySite()?->id ?: 1) ],
            default          => [],
        };
    }
}
