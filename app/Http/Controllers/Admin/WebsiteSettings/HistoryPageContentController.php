<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Storage;

class HistoryPageContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'history_page_hero',
            'history_page_main',
            'history_page_timeline',
            'history_page_seo'
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        // Default structure if empty
        if (!$content['history_page_timeline']) {
            $content['history_page_timeline'] = [];
        }

        return Inertia::render('Admin/WebsiteSettings/HistoryContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Handle Image Uploads for Hero
        if ($request->hasFile('history_page_hero.bg_image_file')) {
            $path = $request->file('history_page_hero.bg_image_file')->store('website/history', 'public');
            $data['history_page_hero']['bg_image'] = $path;
        }

        // Handle Image Uploads for Timeline
        if (isset($data['history_page_timeline']) && is_array($data['history_page_timeline'])) {
            foreach ($data['history_page_timeline'] as $index => $item) {
                if ($request->hasFile("history_page_timeline.{$index}.image_file")) {
                    $path = $request->file("history_page_timeline.{$index}.image_file")->store('website/history', 'public');
                    $data['history_page_timeline'][$index]['image'] = $path;
                }
                unset($data['history_page_timeline'][$index]['image_file']);
            }
        }

        // Remove file objects before saving
        unset($data['history_page_hero']['bg_image_file']);

        $keys = [
            'history_page_hero',
            'history_page_main',
            'history_page_timeline',
            'history_page_seo'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                GlobalSetting::set($key, json_encode($data[$key]));
            }
        }

        return redirect()->back()->with('success', 'History Page Content updated successfully.');
    }
}
