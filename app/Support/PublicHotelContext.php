<?php

namespace App\Support;

use App\Models\Hotel;

/**
 * Request-scoped holder for "which hotel's public site is this request for",
 * set by ResolvePublicHotel middleware on the /hotel/{hotelSlug} route group.
 * CurrentHotel checks this before falling back to Hotel::primarySite() so the
 * same FrontendController/FrontendBookingController code serves any hotel's
 * site without per-controller changes — see App\Support\CurrentHotel.
 */
class PublicHotelContext
{
    private static ?Hotel $hotel = null;

    public static function set(Hotel $hotel): void
    {
        self::$hotel = $hotel;
    }

    public static function get(): ?Hotel
    {
        return self::$hotel;
    }

    /**
     * Resets to "unresolved". Not needed in normal production requests (this is a
     * plain, non-Octane app — every request is a fresh PHP process, so this static
     * starts null regardless) but the test suite makes several requests per PHP
     * process, so tests must reset this between requests to avoid one test's
     * hotel-prefixed request leaking into the next assertion. See tests/TestCase.php.
     */
    public static function clear(): void
    {
        self::$hotel = null;
    }
}
