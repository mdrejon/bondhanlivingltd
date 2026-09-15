<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AboutPageContentController extends Controller
{
    private $keys = [
        'about_page_hero',
        'about_page_company',
        'about_page_achievements',
        'about_page_mission',
        'about_page_process',
        'about_page_seo'
    ];

    public function edit(): Response
    {
        $content = [];
        foreach ($this->keys as $key) {
            $raw = GlobalSetting::get($key);
            $content[$key] = $raw ? json_decode($raw, true) : null;
        }

        return Inertia::render('Admin/WebsiteSettings/AboutContent', [
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
        $imageFields = ['image1', 'image2', 'shape_image', 'bg_image', 'bg_shape'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if (!empty($existingContent[$field]) && Storage::disk('public')->exists($existingContent[$field])) {
                    Storage::disk('public')->delete($existingContent[$field]);
                }
                $data[$field] = $request->file($field)->store('about_content', 'public');
            } else {
                $data[$field] = $existingContent[$field] ?? ($data[$field] ?? null);
            }
        }

        // Handle arrays with icons/files (e.g. features, counters, cards)
        $arrayFields = ['features', 'counters', 'cards'];
        foreach ($arrayFields as $arrayField) {
            if (isset($data[$arrayField]) && is_array($data[$arrayField])) {
                foreach ($data[$arrayField] as $index => $item) {
                    $fileKey = "{$arrayField}.{$index}.icon_file";
                    if ($request->hasFile($fileKey)) {
                        $oldIcon = $existingContent[$arrayField][$index]['icon'] ?? null;
                        if ($oldIcon && Storage::disk('public')->exists($oldIcon)) {
                            Storage::disk('public')->delete($oldIcon);
                        }
                        $data[$arrayField][$index]['icon'] = $request->file($fileKey)->store('about_content/icons', 'public');
                    } else {
                        $data[$arrayField][$index]['icon'] = $existingContent[$arrayField][$index]['icon'] ?? ($item['icon'] ?? null);
                    }
                    unset($data[$arrayField][$index]['icon_file']);

                    // Also handle bg_shape in cards
                    $shapeKey = "{$arrayField}.{$index}.bg_shape_file";
                    if ($request->hasFile($shapeKey)) {
                        $oldShape = $existingContent[$arrayField][$index]['bg_shape'] ?? null;
                        if ($oldShape && Storage::disk('public')->exists($oldShape)) {
                            Storage::disk('public')->delete($oldShape);
                        }
                        $data[$arrayField][$index]['bg_shape'] = $request->file($shapeKey)->store('about_content/shapes', 'public');
                    } else {
                        $data[$arrayField][$index]['bg_shape'] = $existingContent[$arrayField][$index]['bg_shape'] ?? ($item['bg_shape'] ?? null);
                    }
                    unset($data[$arrayField][$index]['bg_shape_file']);
                }
            }
        }

        // Handle nested tabs (Mission section)
        if (isset($data['tabs']) && is_array($data['tabs'])) {
            foreach ($data['tabs'] as $tIndex => $tab) {
                // Tab image
                $tabImageKey = "tabs.{$tIndex}.image_file";
                if ($request->hasFile($tabImageKey)) {
                    $oldTabImg = $existingContent['tabs'][$tIndex]['image'] ?? null;
                    if ($oldTabImg && Storage::disk('public')->exists($oldTabImg)) {
                        Storage::disk('public')->delete($oldTabImg);
                    }
                    $data['tabs'][$tIndex]['image'] = $request->file($tabImageKey)->store('about_content/tabs', 'public');
                } else {
                    $data['tabs'][$tIndex]['image'] = $existingContent['tabs'][$tIndex]['image'] ?? ($tab['image'] ?? null);
                }
                unset($data['tabs'][$tIndex]['image_file']);

                // Checklist doesn't have images

                // Tab features
                if (isset($tab['features']) && is_array($tab['features'])) {
                    foreach ($tab['features'] as $fIndex => $feature) {
                        $fIconKey = "tabs.{$tIndex}.features.{$fIndex}.icon_file";
                        if ($request->hasFile($fIconKey)) {
                            $oldFIcon = $existingContent['tabs'][$tIndex]['features'][$fIndex]['icon'] ?? null;
                            if ($oldFIcon && Storage::disk('public')->exists($oldFIcon)) {
                                Storage::disk('public')->delete($oldFIcon);
                            }
                            $data['tabs'][$tIndex]['features'][$fIndex]['icon'] = $request->file($fIconKey)->store('about_content/tabs_icons', 'public');
                        } else {
                            $data['tabs'][$tIndex]['features'][$fIndex]['icon'] = $existingContent['tabs'][$tIndex]['features'][$fIndex]['icon'] ?? ($feature['icon'] ?? null);
                        }
                        unset($data['tabs'][$tIndex]['features'][$fIndex]['icon_file']);
                    }
                }
            }
        }

        GlobalSetting::set($sectionKey, json_encode($data));

        return back()->with('success', 'Section updated successfully.');
    }
}
