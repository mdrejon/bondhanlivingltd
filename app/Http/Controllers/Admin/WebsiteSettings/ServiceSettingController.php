<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ServiceSettingController extends Controller
{
    private array $keys = [
        // Page hero / breadcrumb
        'svc_page_hero_image',
        'svc_page_hero_title',
        // SEO
        'svc_seo_title',
        'svc_seo_description',
        'svc_seo_keywords',
        'svc_seo_og_image',
        // Homepage section
        'svc_badge',
        'svc_title',
        'svc_desc',
        'svc_btn_text',
        'svc_btn_url',
        // Detail page sidebar "Needs Any Help?" box
        'svc_help_title',
        'svc_help_desc',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->keys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->keys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/WebsiteSettings/Services/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Hero / breadcrumb
            'svc_page_hero_image'   => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'svc_page_hero_title'   => 'nullable|string',
            // SEO
            'svc_seo_title'         => 'nullable|string',
            'svc_seo_description'   => 'nullable|string',
            'svc_seo_keywords'      => 'nullable|string',
            'svc_seo_og_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp',
            // Homepage section
            'svc_badge'             => 'nullable|string',
            'svc_title'             => 'nullable|string',
            'svc_desc'              => 'nullable|string',
            'svc_btn_text'          => 'nullable|string',
            'svc_btn_url'           => 'nullable|string',
            // Detail page sidebar
            'svc_help_title'        => 'nullable|string',
            'svc_help_desc'         => 'nullable|string',
        ]);

        foreach (['svc_page_hero_image', 'svc_seo_og_image'] as $field) {
            if ($request->hasFile($field)) {
                $existing = GlobalSetting::get($field);
                if ($existing) {
                    Storage::disk('public')->delete($existing);
                }
                $data[$field] = $request->file($field)->store('services-settings', 'public');
            } else {
                unset($data[$field]);
            }
        }

        // Auto-generate keywords if none provided
        if (empty($data['svc_seo_keywords'])) {
            $data['svc_seo_keywords'] = $this->autoKeywords(
                $data['svc_seo_title'] ?? GlobalSetting::get('svc_seo_title', ''),
                $data['svc_seo_description'] ?? GlobalSetting::get('svc_seo_description', '')
            );
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Services settings saved.');
    }

    private function autoKeywords(string|null ...$texts): string
    {
        static $stop = [
            'a','an','the','and','or','but','in','on','at','to','for','of','with',
            'by','from','as','is','was','are','were','be','been','being','have',
            'has','had','do','does','did','will','would','could','should','may',
            'might','can','this','that','these','those','it','its','we','our',
            'you','your','hotel','beach','way','cox','bazar',
        ];
        $text  = implode(' ', array_filter($texts, fn($t) => $t !== null));
        $words = preg_split('/\W+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter($words, fn($w) => mb_strlen($w) > 3 && !in_array($w, $stop, true));
        return implode(', ', array_slice(array_unique(array_values($words)), 0, 12));
    }
}
