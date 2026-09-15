<?php

namespace App\Http\Middleware;

use App\Models\GlobalSetting;
use App\Models\Hotel;
use App\Support\CurrentHotel;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user'        => $user,
                'permissions' => $user?->sharedPermissions(), // null = super admin (full access)
                'role_name'   => $user ? ($user->isSuperAdmin() ? 'Super Admin' : ($user->role?->name ?? 'Admin')) : null,
            ],
            'ziggy' => fn () => [
                ...(new \Tighten\Ziggy\Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'stale'   => fn () => $request->session()->get('stale'),
            ],
            'site' => fn () => [
                'name'      => GlobalSetting::get('site_name', 'Hotel Beach Way'),
                'logo'      => GlobalSetting::get('header_logo'),
                'tagline'   => GlobalSetting::get('site_tagline', 'Your Perfect Beachside Escape'),
                'phone'     => GlobalSetting::get('contact_phone', GlobalSetting::get('header_phone', '')),
                'email'     => GlobalSetting::get('contact_email', GlobalSetting::get('header_email', '')),
                'address'   => GlobalSetting::get('contact_address', ''),
                'facebook'  => GlobalSetting::get('footer_facebook', ''),
                'instagram' => GlobalSetting::get('footer_instagram', ''),
                'twitter'   => GlobalSetting::get('footer_twitter', ''),
                'youtube'   => GlobalSetting::get('footer_youtube', ''),
                'copyright' => GlobalSetting::get('footer_copyright', '© ' . date('Y') . ' Hotel Beach Way. All rights reserved.'),
            ],
            'actingHotel' => fn () => $user?->isSuperAdmin()
                ? [
                    'hotels'  => Hotel::orderBy('name')->get(['id', 'name']),
                    'current' => CurrentHotel::homeId(),
                ]
                : null,
        ];
    }
}
