<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomAmenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomAmenityController extends Controller
{
    public function index(): Response
    {
        $amenities = RoomAmenity::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Admin/RoomAmenities/Index', [
            'amenities' => $amenities,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'icon_svg'   => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        RoomAmenity::create($data);

        return back()->with('success', 'Amenity created successfully.');
    }

    public function update(Request $request, RoomAmenity $roomAmenity): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'icon_svg'   => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $roomAmenity->update($data);

        return back()->with('success', 'Amenity updated successfully.');
    }

    public function destroy(RoomAmenity $roomAmenity): RedirectResponse
    {
        $roomAmenity->delete();

        return back()->with('success', 'Amenity deleted.');
    }
}
