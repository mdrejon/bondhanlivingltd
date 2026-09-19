<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Storage;

class CorporateBackgroundContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'corporate_page_hero',
            'corporate_page_main',
            'corporate_page_seo'
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/CorporateBackgroundContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Handle Image Uploads for Hero
        if ($request->hasFile('corporate_page_hero.bg_image_file')) {
            $path = $request->file('corporate_page_hero.bg_image_file')->store('website/corporate', 'public');
            $data['corporate_page_hero']['bg_image'] = $path;
        }
        unset($data['corporate_page_hero']['bg_image_file']);

        // Handle Image Uploads for Main Area
        if ($request->hasFile('corporate_page_main.bg_image_file')) {
            $path = $request->file('corporate_page_main.bg_image_file')->store('website/corporate', 'public');
            $data['corporate_page_main']['bg_image'] = $path;
        }
        unset($data['corporate_page_main']['bg_image_file']);

        if ($request->hasFile('corporate_page_main.image_file')) {
            $path = $request->file('corporate_page_main.image_file')->store('website/corporate', 'public');
            $data['corporate_page_main']['image'] = $path;
        }
        unset($data['corporate_page_main']['image_file']);

        $keys = [
            'corporate_page_hero',
            'corporate_page_main',
            'corporate_page_seo'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                GlobalSetting::set($key, json_encode($data[$key]));
            }
        }

        return redirect()->back()->with('success', 'Corporate Background Content updated successfully.');
    }
}
