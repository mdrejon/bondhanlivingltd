<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RoomsSettingController extends Controller
{
    private array $keys = [
        'rooms_hero_image',
        'rooms_hero_title',
        'rooms_seo_title',
        'rooms_seo_description',
        'rooms_seo_keywords',
        'rooms_seo_og_image',
        'rooms_badge',
        'rooms_title',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->keys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->keys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/WebsiteSettings/Rooms/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'rooms_hero_title'      => 'nullable|string',
            'rooms_seo_title'       => 'nullable|string',
            'rooms_seo_description' => 'nullable|string',
            'rooms_seo_keywords'    => 'nullable|string',
            'rooms_badge'           => 'nullable|string',
            'rooms_title'           => 'nullable|string',
            'rooms_hero_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'rooms_seo_og_image'    => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        foreach (['rooms_hero_image', 'rooms_seo_og_image'] as $imgKey) {
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

        if (empty($data['rooms_seo_keywords'])) {
            $data['rooms_seo_keywords'] = $this->autoKeywords(
                $data['rooms_seo_title'] ?? GlobalSetting::get('rooms_seo_title', ''),
                $data['rooms_seo_description'] ?? GlobalSetting::get('rooms_seo_description', '')
            );
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Rooms page settings saved.');
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
