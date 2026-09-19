<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class GalleryPageContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'gallery_page_hero',
            'gallery_page_images',
            'gallery_page_seo',
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/GalleryContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        // 1. gallery_page_hero
        if ($request->has('gallery_page_hero')) {
            $data = $request->input('gallery_page_hero');
            if ($request->hasFile('gallery_page_hero.bg_image_file')) {
                $path = $request->file('gallery_page_hero.bg_image_file')->store('website-settings', 'public');
                $data['bg_image'] = $path;
            } else {
                $oldData = json_decode(GlobalSetting::get('gallery_page_hero', '{}'), true);
                $data['bg_image'] = $oldData['bg_image'] ?? '';
            }
            unset($data['bg_image_file']);
            GlobalSetting::set('gallery_page_hero', json_encode($data));
        }

        // 2. gallery_page_images
        if ($request->has('gallery_page_images')) {
            $imagesData = $request->input('gallery_page_images');
            
            // Handle file uploads for gallery images
            foreach ($imagesData as $index => &$imgItem) {
                if ($request->hasFile("gallery_page_images.{$index}.image_file")) {
                    $path = $request->file("gallery_page_images.{$index}.image_file")->store('website-settings/gallery', 'public');
                    $imgItem['image'] = $path;
                } else {
                    $oldImages = json_decode(GlobalSetting::get('gallery_page_images', '[]'), true) ?? [];
                    $imgItem['image'] = $oldImages[$index]['image'] ?? ($imgItem['image'] ?? '');
                }
                unset($imgItem['image_file']);
            }
            
            GlobalSetting::set('gallery_page_images', json_encode($imagesData));
        }

        // 3. gallery_page_seo
        if ($request->has('gallery_page_seo')) {
            $seoData = $request->input('gallery_page_seo');
            GlobalSetting::set('gallery_page_seo', json_encode($seoData));
        }

        return redirect()->back()->with('success', 'Gallery settings updated successfully.');
    }
}
