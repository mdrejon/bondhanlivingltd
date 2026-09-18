<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HeaderSettingController extends Controller
{
    public function edit()
    {
        return Inertia::render('Admin/WebsiteSettings/Header/Edit', [
            'settings' => [
                'header_phone' => GlobalSetting::get('header_phone', '+8801740 574 490'),
                'header_email' => GlobalSetting::get('header_email', 'info@bondhanlivingltd.com'),
                'social_facebook' => GlobalSetting::get('social_facebook', 'https://www.facebook.com/bondhanlivingltd/'),
                'social_twitter' => GlobalSetting::get('social_twitter', 'https://www.twitter.com/'),
                'social_linkedin' => GlobalSetting::get('social_linkedin', 'https://www.linkedin.com/'),
                'social_instagram' => GlobalSetting::get('social_instagram', 'https://www.instagram.com/'),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'header_phone' => 'nullable|string|max:255',
            'header_email' => 'nullable|email|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);

        GlobalSetting::setMany($validated);

        return back()->with('success', 'Header settings updated successfully.');
    }
}
