<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Lets a Super Admin pick which hotel's website/settings they're currently
 * managing (see App\Support\CurrentHotel::homeId()). Session-only override —
 * no new module/permission, and it's meaningless for anyone but a super admin
 * (a hotel-role account already has exactly one hotel).
 */
class ActingHotelController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
        ]);

        if ($data['hotel_id'] ?? null) {
            $request->session()->put('acting_hotel_id', (int) $data['hotel_id']);
        } else {
            $request->session()->forget('acting_hotel_id');
        }

        return back();
    }
}
