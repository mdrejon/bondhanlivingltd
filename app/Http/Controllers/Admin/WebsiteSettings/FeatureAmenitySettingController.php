<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeatureAmenitySettingController extends Controller
{
    public function edit()
    {
        $features = json_decode(GlobalSetting::get('features_list', '[]'), true) ?? [];
        $amenities = json_decode(GlobalSetting::get('amenities_list', '[]'), true) ?? [];

        return Inertia::render('Admin/WebsiteSettings/FeatureAmenity/Edit', [
            'features' => $features,
            'amenities' => $amenities,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'features' => 'array',
            'features.*' => 'string|max:1000',
            'amenities' => 'array',
            'amenities.*.category' => 'required|string|max:255',
            'amenities.*.items' => 'array',
            'amenities.*.items.*' => 'string|max:1000',
        ]);

        GlobalSetting::set('features_list', json_encode($validated['features'] ?? []));
        GlobalSetting::set('amenities_list', json_encode($validated['amenities'] ?? []));

        return back()->with('success', 'Features & Amenities updated successfully.');
    }
}
