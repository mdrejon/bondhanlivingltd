<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Storage;

class ContactPageContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'contact_page_hero',
            'contact_page_info',
            'contact_page_map',
            'contact_page_seo'
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/ContactContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Handle Image Uploads for Hero
        if ($request->hasFile('contact_page_hero.bg_image_file')) {
            $path = $request->file('contact_page_hero.bg_image_file')->store('website/contact', 'public');
            $data['contact_page_hero']['bg_image'] = $path;
        }
        unset($data['contact_page_hero']['bg_image_file']);

        $keys = [
            'contact_page_hero',
            'contact_page_info',
            'contact_page_map',
            'contact_page_seo'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                GlobalSetting::set($key, json_encode($data[$key]));
            }
        }

        return redirect()->back()->with('success', 'Contact Page Content updated successfully.');
    }
}
