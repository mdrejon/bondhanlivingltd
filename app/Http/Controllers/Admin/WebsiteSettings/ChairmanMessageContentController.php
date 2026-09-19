<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Storage;

class ChairmanMessageContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'chairman_page_hero',
            'chairman_page_main',
            'chairman_page_info',
            'chairman_page_seo'
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/ChairmanMessageContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Handle Image Uploads for Hero
        if ($request->hasFile('chairman_page_hero.bg_image_file')) {
            $path = $request->file('chairman_page_hero.bg_image_file')->store('website/chairman', 'public');
            $data['chairman_page_hero']['bg_image'] = $path;
        }
        unset($data['chairman_page_hero']['bg_image_file']);

        // Handle Image Uploads for Main Area
        if ($request->hasFile('chairman_page_main.bg_image_file')) {
            $path = $request->file('chairman_page_main.bg_image_file')->store('website/chairman', 'public');
            $data['chairman_page_main']['bg_image'] = $path;
        }
        unset($data['chairman_page_main']['bg_image_file']);

        if ($request->hasFile('chairman_page_main.image_file')) {
            $path = $request->file('chairman_page_main.image_file')->store('website/chairman', 'public');
            $data['chairman_page_main']['image'] = $path;
        }
        unset($data['chairman_page_main']['image_file']);

        $keys = [
            'chairman_page_hero',
            'chairman_page_main',
            'chairman_page_info',
            'chairman_page_seo'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                GlobalSetting::set($key, json_encode($data[$key]));
            }
        }

        return redirect()->back()->with('success', 'Chairman Message Content updated successfully.');
    }
}
