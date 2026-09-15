<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class FaqPageSettingController extends Controller
{
    private array $keys = [
        'faq_hero_image',
        'faq_hero_title',
        'faq_seo_title',
        'faq_seo_description',
        'faq_seo_keywords',
        'faq_seo_og_image',
        'faq_page_badge',
        'faq_page_title',
        'faq_page_desc',
        'faq_page_image',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->keys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->keys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/WebsiteSettings/Faq/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'faq_hero_title'      => 'nullable|string',
            'faq_seo_title'       => 'nullable|string',
            'faq_seo_description' => 'nullable|string',
            'faq_seo_keywords'    => 'nullable|string',
            'faq_page_badge'      => 'nullable|string',
            'faq_page_title'      => 'nullable|string',
            'faq_page_desc'       => 'nullable|string',
            'faq_hero_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'faq_seo_og_image'    => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'faq_page_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        foreach (['faq_hero_image', 'faq_seo_og_image', 'faq_page_image'] as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $existing = GlobalSetting::get($imgKey);
                if ($existing) {
                    Storage::disk('public')->delete($existing);
                }
                $data[$imgKey] = $request->file($imgKey)->store('settings', 'public');
            } else {
                unset($data[$imgKey]);
            }
        }

        // Auto-generate keywords if none provided
        if (empty($data['faq_seo_keywords'])) {
            $data['faq_seo_keywords'] = $this->autoKeywords(
                $data['faq_seo_title'] ?? GlobalSetting::get('faq_seo_title', ''),
                $data['faq_seo_description'] ?? GlobalSetting::get('faq_seo_description', '')
            );
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'FAQ page settings saved.');
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
