<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class TermsConditionContentController extends Controller
{
    public function edit()
    {
        $keys = [
            'terms_page_hero',
            'terms_page_items',
            'terms_page_seo',
        ];

        $settings = GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $content = [];
        foreach ($keys as $key) {
            $content[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/TermsConditionContent', [
            'content' => $content
        ]);
    }

    public function update(Request $request)
    {
        // 1. terms_page_hero
        if ($request->has('terms_page_hero')) {
            $data = $request->input('terms_page_hero');
            if ($request->hasFile('terms_page_hero.bg_image_file')) {
                $path = $request->file('terms_page_hero.bg_image_file')->store('website-settings', 'public');
                $data['bg_image'] = $path;
            } else {
                $oldData = json_decode(GlobalSetting::get('terms_page_hero', '{}'), true);
                $data['bg_image'] = $oldData['bg_image'] ?? '';
            }
            unset($data['bg_image_file']);
            GlobalSetting::set('terms_page_hero', json_encode($data));
        }

        // 2. terms_page_items
        if ($request->has('terms_page_items')) {
            $itemsData = $request->input('terms_page_items');
            GlobalSetting::set('terms_page_items', json_encode($itemsData));
        }

        // 3. terms_page_seo
        if ($request->has('terms_page_seo')) {
            $seoData = $request->input('terms_page_seo');
            GlobalSetting::set('terms_page_seo', json_encode($seoData));
        }

        return redirect()->back()->with('success', 'Terms & Conditions settings updated successfully.');
    }
}
