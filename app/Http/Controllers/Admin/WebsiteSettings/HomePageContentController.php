<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class HomePageContentController extends Controller
{
    private $keys = [
        'home_page_why_choose_us',
        'home_page_service',
        'home_page_cta',
        'home_page_team',
        'home_page_project',
        'home_page_achievements',
        'home_page_testimonial',
        'home_page_blog',
        'home_page_seo',
    ];

    public function edit(): Response
    {
        $content = [];
        foreach ($this->keys as $key) {
            $raw = GlobalSetting::get($key);
            $content[$key] = $raw ? json_decode($raw, true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/HomeContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $sectionKey = $request->input('section_key');
        if (!in_array($sectionKey, $this->keys)) {
            return back()->with('error', 'Invalid section key.');
        }

        $raw = GlobalSetting::get($sectionKey);
        $existingContent = $raw ? json_decode($raw, true) : [];
        $data = $request->except(['_token', 'section_key']);

        // Handle generic single image uploads
        $imageFields = ['image', 'bg_image', 'side_image'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if (!empty($existingContent[$field]) && Storage::disk('public')->exists($existingContent[$field])) {
                    Storage::disk('public')->delete($existingContent[$field]);
                }
                $data[$field] = $request->file($field)->store('home_content', 'public');
            } else {
                $data[$field] = $existingContent[$field] ?? ($data[$field] ?? null);
            }
        }

        // Handle arrays with icons/files (e.g. features or counters)
        $arrayFields = ['features', 'counters'];
        foreach ($arrayFields as $arrayField) {
            if (isset($data[$arrayField]) && is_array($data[$arrayField])) {
                foreach ($data[$arrayField] as $index => $item) {
                    $fileKey = "{$arrayField}.{$index}.icon_file";
                    if ($request->hasFile($fileKey)) {
                        $oldIcon = $existingContent[$arrayField][$index]['icon'] ?? null;
                        if ($oldIcon && Storage::disk('public')->exists($oldIcon)) {
                            Storage::disk('public')->delete($oldIcon);
                        }
                        $data[$arrayField][$index]['icon'] = $request->file($fileKey)->store('home_content/icons', 'public');
                    } else {
                        // Keep existing icon
                        $data[$arrayField][$index]['icon'] = $existingContent[$arrayField][$index]['icon'] ?? ($item['icon'] ?? null);
                    }
                    // Remove the uploaded file instance from the array before saving to JSON
                    unset($data[$arrayField][$index]['icon_file']);
                }
            }
        }

        GlobalSetting::set($sectionKey, json_encode($data));

        return back()->with('success', 'Section updated successfully.');
    }
}
