<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HistorySettingController extends Controller
{
    private array $keys = [
        'hist_hero_image',
        'hist_hero_title',
        'hist_badge',
        'hist_title',
        'hist_desc',
        'hist_timeline',
        'hist_seo_title',
        'hist_seo_description',
        'hist_seo_keywords',
        'hist_seo_og_image',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->keys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->keys as $key) {
            $settings[$key] ??= null;
        }

        $settings['hist_timeline'] = $settings['hist_timeline']
            ? json_decode($settings['hist_timeline'], true)
            : $this->defaultTimeline();

        return Inertia::render('Admin/WebsiteSettings/History/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hist_hero_image'       => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'hist_hero_title'       => 'nullable|string',
            'hist_badge'            => 'nullable|string',
            'hist_title'            => 'nullable|string',
            'hist_desc'             => 'nullable|string',
            'hist_timeline'         => 'nullable|array',
            'hist_timeline.*.year'     => 'nullable|string',
            'hist_timeline.*.tag'      => 'nullable|string',
            'hist_timeline.*.heading'  => 'nullable|string',
            'hist_timeline.*.content'  => 'nullable|string',
            'hist_timeline.*.badges'   => 'nullable|array',
            'hist_timeline.*.badges.*' => 'nullable|string',
            'hist_timeline.*.image'    => 'nullable|string',
            'hist_timeline.*.reversed' => 'nullable|boolean',
            'timeline_images'          => 'nullable|array',
            'timeline_images.*'        => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'hist_seo_title'        => 'nullable|string',
            'hist_seo_description'  => 'nullable|string',
            'hist_seo_keywords'     => 'nullable|string',
            'hist_seo_og_image'     => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        // Hero image
        if ($request->hasFile('hist_hero_image')) {
            $existing = GlobalSetting::get('hist_hero_image');
            if ($existing) Storage::disk('public')->delete($existing);
            $data['hist_hero_image'] = $request->file('hist_hero_image')->store('history', 'public');
        } else {
            unset($data['hist_hero_image']);
        }

        // OG image
        if ($request->hasFile('hist_seo_og_image')) {
            $existing = GlobalSetting::get('hist_seo_og_image');
            if ($existing) Storage::disk('public')->delete($existing);
            $data['hist_seo_og_image'] = $request->file('hist_seo_og_image')->store('history', 'public');
        } else {
            unset($data['hist_seo_og_image']);
        }

        // Timeline item images — indexed file uploads
        $timeline = $data['hist_timeline'] ?? [];
        if ($request->hasFile('timeline_images')) {
            foreach ($request->file('timeline_images') as $idx => $file) {
                if (!$file) continue;
                // Delete old image if replacing
                if (!empty($timeline[$idx]['image'])) {
                    Storage::disk('public')->delete($timeline[$idx]['image']);
                }
                $timeline[$idx]['image'] = $file->store('history/timeline', 'public');
            }
        }

        // Clean up timeline: filter empty items, ensure badges is array
        $timeline = array_values(array_filter($timeline, fn($i) => !empty($i['year']) || !empty($i['heading'])));
        foreach ($timeline as &$item) {
            $item['badges']   = array_values(array_filter($item['badges'] ?? []));
            $item['reversed'] = (bool) ($item['reversed'] ?? false);
        }
        unset($item);

        $data['hist_timeline'] = json_encode($timeline);
        unset($data['timeline_images']);

        // Auto-generate keywords if none provided
        if (empty($data['hist_seo_keywords'])) {
            $data['hist_seo_keywords'] = $this->autoKeywords(
                $data['hist_seo_title'] ?? GlobalSetting::get('hist_seo_title', ''),
                $data['hist_seo_description'] ?? GlobalSetting::get('hist_seo_description', ''),
                $data['hist_title'] ?? GlobalSetting::get('hist_title', '')
            );
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'History page settings saved.');
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

    private function defaultTimeline(): array
    {
        return [
            ['year' => '2013', 'tag' => 'Foundation',      'heading' => 'Grand Opening — Hotel Beach Way Is Born',        'content' => 'With a vision to redefine coastal hospitality, Hotel Beach Way opened its doors on Kolatoli Road, Cox\'s Bazar in 2013.', 'badges' => ['30 Rooms Launched', '24/7 Front Desk', 'Kolatoli Beach Access'], 'image' => '', 'reversed' => false],
            ['year' => '2015', 'tag' => 'Dining',          'heading' => 'Dew Drop Restaurant Opens Its Doors',            'content' => 'In 2015, Hotel Beach Way unveiled the Dew Drop Restaurant — a full-service dining experience offering local Bangladeshi flavors and international cuisine.', 'badges' => ['Dew Drop Restaurant', 'Buffet Breakfast', 'BBQ Evenings'], 'image' => '', 'reversed' => true],
            ['year' => '2017', 'tag' => 'Expansion',       'heading' => 'Premium Room Renovation & Suite Expansion',       'content' => '2017 marked a major transformation. All rooms were upgraded and a new wing introduced the Honeymoon Suite and Executive Suite.', 'badges' => ['Honeymoon Suite Added', 'Executive Suite', 'Smart Room Upgrades'], 'image' => '', 'reversed' => false],
            ['year' => '2019', 'tag' => 'Recreation',      'heading' => 'Swimming Pool & Recreation Centre Inaugurated',   'content' => '2019 saw the launch of our outdoor swimming pool and dedicated jogging track — completing our resort-style transformation.', 'badges' => ['Outdoor Pool', 'Jogging Track', 'Resort Experience'], 'image' => '', 'reversed' => true],
            ['year' => '2021', 'tag' => 'Recognition',     'heading' => 'Award-Winning Hospitality & Guest Excellence',   'content' => 'By 2021, Hotel Beach Way earned recognition as one of Cox\'s Bazar\'s highest-rated hospitality destinations.', 'badges' => ['Best Hotel Award', 'Top-Rated on Travel Platforms', 'Guest Excellence'], 'image' => '', 'reversed' => false],
            ['year' => '2023', 'tag' => 'Wellness & Events','heading' => 'Spa, Gym & Conference Hall Launched',            'content' => '2023 brought our most ambitious expansion with a Spa & Massage Centre, Gym, and multipurpose Conference & Banquet Hall.', 'badges' => ['Spa & Massage', 'Gym Centre', 'Conference Hall'], 'image' => '', 'reversed' => true],
            ['year' => '2025', 'tag' => 'A Decade & Beyond','heading' => 'Celebrating Excellence — The Journey Continues', 'content' => 'Over a decade after opening our doors, Hotel Beach Way stands as a symbol of warmth, quality, and coastal luxury in Cox\'s Bazar.', 'badges' => ['10+ Years of Service', '10,000+ Happy Guests', 'Continuously Growing'], 'image' => '', 'reversed' => false],
        ];
    }
}
