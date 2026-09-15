<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use App\Support\PublicHotelContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the {hotelSlug} segment on the /hotel/{hotelSlug}/... public route
 * group to a real, active Hotel and records it via PublicHotelContext before the
 * controller runs. 404s for an unknown/inactive slug rather than silently falling
 * back to the primary site — a wrong/typo'd URL should not leak a different
 * hotel's content.
 */
class ResolvePublicHotel
{
    public function handle(Request $request, Closure $next): Response
    {
        $hotel = Hotel::where('slug', $request->route('hotelSlug'))
            ->where('status', 'active')
            ->firstOrFail();

        PublicHotelContext::set($hotel);

        return $next($request);
    }
}
