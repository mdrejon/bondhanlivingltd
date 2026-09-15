<?php

namespace App\Providers;

use App\Models\GlobalSetting;

use App\Support\EmailNotificationSettings;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    /**
     * Process-wide default mail config, applied before any request-specific hotel
     * context exists (this runs during provider boot, ahead of the session/auth
     * middleware and ResolvePublicHotel) — always the primary hotel's SMTP config.
     * Anything that sends mail for a *specific* hotel's record (a booking/inquiry)
     * must re-apply that hotel's config right before sending — see
     * EmailNotificationSettings::applyMailConfigFor() and its call sites in
     * FrontendController/FrontendBookingController.
     */
    private function bootMailConfig(): void
    {
        try {
            EmailNotificationSettings::applyMailConfigFor(0);
        } catch (\Throwable) {
            // DB not ready (e.g. during migrations) — fall back to .env
        }
    }

    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));

        $this->bootMailConfig();


        View::composer('*', function ($view) {
            $headerKeys = [
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

            $footerKeys = [
                'footer_logo',
                'footer_brand_description',
                'footer_facebook_url',
                'footer_twitter_url',
                'footer_instagram_url',
                'footer_youtube_url',
                'footer_quick_links',
                'footer_service_links',
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

            $popupKeys = [
                'sidebar_about_title',
                'sidebar_about_text',
                'sidebar_quote_title',
                'sidebar_quote_btn_text',
                'contact_widget_title',
                'contact_widget_subtitle',
                'contact_widget_btn_text',
            ];

            $allKeys = array_merge($headerKeys, $footerKeys, $popupKeys);

            $raw = GlobalSetting::whereIn('key', $allKeys)
                ->pluck('value', 'key')
                ->toArray();

            $headerSettings = [];
            foreach ($headerKeys as $key) {
                $headerSettings[$key] = $raw[$key] ?? null;
            }

            $footerSettings = [];
            foreach ($footerKeys as $key) {
                $footerSettings[$key] = $raw[$key] ?? null;
            }

            foreach (['footer_quick_links', 'footer_service_links'] as $jsonKey) {
                $footerSettings[$jsonKey] = !empty($footerSettings[$jsonKey])
                    ? json_decode($footerSettings[$jsonKey], true)
                    : [];
            }

            $popupSettings = [];
            foreach ($popupKeys as $key) {
                $popupSettings[$key] = $raw[$key] ?? null;
            }

            $view->with('headerSettings', $headerSettings);
            $view->with('footerSettings', $footerSettings);
            $view->with('popupSettings', $popupSettings);
        });
    }
}
