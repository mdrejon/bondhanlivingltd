<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HeaderSettingController extends Controller
{
    private array $headerKeys = [
        'header_logo',
        'header_phone',
        'header_email',
        'header_address',
        'header_facebook_url',
        'header_twitter_url',
        'header_instagram_url',
        'header_pinterest_url',
        'header_book_btn_text',
        'header_book_btn_url',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->headerKeys)
            ->pluck('value', 'key')
            ->toArray();

        // Fill missing keys with null so Vue always has all fields
        foreach ($this->headerKeys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/WebsiteSettings/Header/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'header_phone'         => 'nullable|string',
            'header_email'         => 'nullable|email',
            'header_address'       => 'nullable|string',
            'header_facebook_url'  => 'nullable|string',
            'header_twitter_url'   => 'nullable|string',
            'header_instagram_url' => 'nullable|string',
            'header_pinterest_url' => 'nullable|string',
            'header_book_btn_text' => 'nullable|string',
            'header_book_btn_url'  => 'nullable|string',
            'header_logo'          => 'nullable|image|mimes:jpeg,jpg,png,webp,svg',
        ]);

        if ($request->hasFile('header_logo')) {
            $existing = GlobalSetting::get('header_logo');
            if ($existing) {
                Storage::disk('public')->delete($existing);
            }
            $data['header_logo'] = $request->file('header_logo')
                ->store('settings', 'public');
        } else {
            unset($data['header_logo']);
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Header settings saved.');
    }
}
