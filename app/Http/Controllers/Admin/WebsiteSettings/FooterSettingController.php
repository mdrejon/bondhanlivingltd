<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class FooterSettingController extends Controller
{
    private array $footerKeys = [
        'footer_logo',
        'footer_brand_description',
        'footer_facebook_url',
        'footer_twitter_url',
        'footer_instagram_url',
        'footer_youtube_url',
        'footer_quick_links',         // JSON array [{label, url}]
        'footer_service_links',       // JSON array [{label, url}]
        'footer_phone_1',
        'footer_phone_2',
        'footer_phone_3',
        'footer_email_1',
        'footer_email_2',
        'footer_address_line1',
        'footer_address_line2',
        'footer_website_url',
        'footer_newsletter_title',
        'footer_privacy_url',
        'footer_terms_url',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->footerKeys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->footerKeys as $key) {
            $settings[$key] ??= null;
        }

        // Decode JSON fields
        foreach (['footer_quick_links', 'footer_service_links'] as $jsonKey) {
            if (!empty($settings[$jsonKey])) {
                $settings[$jsonKey] = json_decode($settings[$jsonKey], true);
            } else {
                $settings[$jsonKey] = [];
            }
        }

        return Inertia::render('Admin/WebsiteSettings/Footer/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'footer_logo'              => 'nullable|image|mimes:jpeg,jpg,png,webp,svg',
            'footer_brand_description' => 'nullable|string',
            'footer_facebook_url'      => 'nullable|string',
            'footer_twitter_url'       => 'nullable|string',
            'footer_instagram_url'     => 'nullable|string',
            'footer_youtube_url'       => 'nullable|string',
            'footer_quick_links'       => 'nullable|array',
            'footer_quick_links.*.label' => 'required|string',
            'footer_quick_links.*.url'   => 'required|string',
            'footer_service_links'       => 'nullable|array',
            'footer_service_links.*.label' => 'required|string',
            'footer_service_links.*.url'   => 'required|string',
            'footer_phone_1'           => 'nullable|string',
            'footer_phone_2'           => 'nullable|string',
            'footer_phone_3'           => 'nullable|string',
            'footer_email_1'           => 'nullable|email',
            'footer_email_2'           => 'nullable|email',
            'footer_address_line1'     => 'nullable|string',
            'footer_address_line2'     => 'nullable|string',
            'footer_website_url'       => 'nullable|string',
            'footer_newsletter_title'  => 'nullable|string',
            'footer_privacy_url'       => 'nullable|string',
            'footer_terms_url'         => 'nullable|string',
        ]);

        if ($request->hasFile('footer_logo')) {
            $existing = GlobalSetting::get('footer_logo');
            if ($existing) {
                Storage::disk('public')->delete($existing);
            }
            $data['footer_logo'] = $request->file('footer_logo')
                ->store('settings', 'public');
        } else {
            unset($data['footer_logo']);
        }

        // Encode JSON fields before saving
        foreach (['footer_quick_links', 'footer_service_links'] as $jsonKey) {
            if (isset($data[$jsonKey])) {
                $data[$jsonKey] = json_encode($data[$jsonKey]);
            }
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Footer settings saved.');
    }
}
