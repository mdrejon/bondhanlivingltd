<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GlobalSetting;

class ProjectPageContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'project_page_hero',
            'project_page_seo'
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/ProjectContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // Handle Image Uploads for Hero Background
        if ($request->hasFile('project_page_hero.bg_image_file')) {
            $path = $request->file('project_page_hero.bg_image_file')->store('website/project', 'public');
            $data['project_page_hero']['bg_image'] = $path;
        }

        // Remove file objects before saving
        unset($data['project_page_hero']['bg_image_file']);

        $keys = [
            'project_page_hero',
            'project_page_seo'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                GlobalSetting::set($key, json_encode($data[$key]));
            }
        }

        return redirect()->back()->with('success', 'Project Page Content updated successfully.');
    }
}
