<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AboutSettingController extends Controller
{
    private array $aboutKeys = [
        // Page hero / breadcrumb
        'about_hero_image',
        'about_hero_title',
        // SEO
        'about_seo_title',
        'about_seo_description',
        'about_seo_keywords',
        'about_seo_og_image',
        // Deco badges
        'about_deco_badge_value',
        'about_deco_badge_label',
        'about_deco_years_value',
        'about_deco_years_label',
        // Left column
        'about_badge',
        'about_title',
        'about_desc',
        'about_main_image',
        'about_main_image_alt',
        // Right column
        'about_top_image',
        'about_top_image_alt',
        // Mission tab
        'about_mission_label',
        'about_mission_text',
        'about_mission_features',
        // Vision tab
        'about_vision_label',
        'about_vision_text',
        'about_vision_features',
        // History tab
        'about_history_label',
        'about_history_text',
        'about_history_features',
        // CTA button
        'about_more_btn_text',
        'about_more_btn_url',
        // Stats counter
        'about_stats',
        // Video section
        'about_video_thumb',
        'about_video_url',
        // Why Choose Us
        'why_badge',
        'why_title',
        'why_desc',
        'why_features',
    ];

    /** JSON-encoded keys that hold arrays */
    private array $jsonKeys = [
        'about_mission_features',
        'about_vision_features',
        'about_history_features',
        'about_stats',
        'why_features',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->aboutKeys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->aboutKeys as $key) {
            $settings[$key] ??= null;
        }

        // Decode JSON arrays for Vue
        foreach ($this->jsonKeys as $key) {
            $settings[$key] = $settings[$key]
                ? json_decode($settings[$key], true)
                : [];
        }

        return Inertia::render('Admin/WebsiteSettings/About/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Hero / breadcrumb
            'about_hero_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'about_hero_title'         => 'nullable|string',
            // SEO
            'about_seo_title'          => 'nullable|string',
            'about_seo_description'    => 'nullable|string',
            'about_seo_keywords'       => 'nullable|string',
            'about_seo_og_image'       => 'nullable|image|mimes:jpeg,jpg,png,webp',
            // Deco badges
            'about_deco_badge_value'   => 'nullable|string',
            'about_deco_badge_label'   => 'nullable|string',
            'about_deco_years_value'   => 'nullable|string',
            'about_deco_years_label'   => 'nullable|string',
            'about_badge'              => 'nullable|string',
            'about_title'              => 'nullable|string',
            'about_desc'               => 'nullable|string',
            // 'about_main_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'about_main_image_alt'     => 'nullable|string',
            'about_top_image'          => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'about_top_image_alt'      => 'nullable|string',
            'about_mission_label'      => 'nullable|string',
            'about_mission_text'       => 'nullable|string',
            'about_mission_features'   => 'nullable|array',
            'about_mission_features.*' => 'nullable|string',
            'about_vision_label'       => 'nullable|string',
            'about_vision_text'        => 'nullable|string',
            'about_vision_features'    => 'nullable|array',
            'about_vision_features.*'  => 'nullable|string',
            'about_history_label'      => 'nullable|string',
            'about_history_text'       => 'nullable|string',
            'about_history_features'   => 'nullable|array',
            'about_history_features.*' => 'nullable|string',
            'about_more_btn_text'      => 'nullable|string',
            'about_more_btn_url'       => 'nullable|string',
            // Stats
            'about_stats'              => 'nullable|array',
            'about_stats.*.value'      => 'nullable|string',
            'about_stats.*.suffix'     => 'nullable|string',
            'about_stats.*.label'      => 'nullable|string',
            // Video
            'about_video_thumb'        => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'about_video_url'          => 'nullable|string',
            // Why Choose Us
            'why_badge'                => 'nullable|string',
            'why_title'                => 'nullable|string',
            'why_desc'                 => 'nullable|string',
            'why_features'             => 'nullable|array',
            'why_features.*.icon_svg'  => 'nullable|string',
            'why_features.*.title'     => 'nullable|string',
            'why_features.*.description' => 'nullable|string',
        ]);

        // Handle image uploads
        foreach (['about_hero_image', 'about_seo_og_image', 'about_main_image', 'about_top_image', 'about_video_thumb'] as $field) {
            if ($request->hasFile($field)) {
                $existing = GlobalSetting::get($field);
                if ($existing) {
                    Storage::disk('public')->delete($existing);
                }
                $data[$field] = $request->file($field)->store('about', 'public');
            } else {
                unset($data[$field]);
            }
        }

        // Auto-generate keywords if none provided
        if (empty($data['about_seo_keywords'])) {
            $data['about_seo_keywords'] = $this->autoKeywords(
                $data['about_seo_title'] ?? GlobalSetting::get('about_seo_title', ''),
                $data['about_seo_description'] ?? GlobalSetting::get('about_seo_description', ''),
                $data['about_title'] ?? GlobalSetting::get('about_title', '')
            );
        }

        // Encode array fields as JSON
        foreach ($this->jsonKeys as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                if (in_array($key, ['about_mission_features', 'about_vision_features', 'about_history_features'])) {
                    $data[$key] = json_encode(array_values(array_filter($data[$key])));
                } else {
                    $data[$key] = json_encode(array_values($data[$key]));
                }
            }
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'About section settings saved.');
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
