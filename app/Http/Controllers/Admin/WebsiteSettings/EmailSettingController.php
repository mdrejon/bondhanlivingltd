<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use App\Support\EmailNotificationSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailSettingController extends Controller
{
    private array $toggleDefaults = [
        'email_toggle_new_booking_customer'            => false,
        'email_toggle_new_booking_admin'                => true,
        'email_toggle_status_confirmed_customer'        => true,
        'email_toggle_status_confirmed_admin'           => false,
        'email_toggle_status_payment_pending_customer'  => true,
        'email_toggle_status_payment_pending_admin'     => false,
        'email_toggle_status_checked_in_customer'       => true,
        'email_toggle_status_checked_in_admin'          => false,
        'email_toggle_status_checked_out_customer'      => true,
        'email_toggle_status_checked_out_admin'         => false,
        'email_toggle_status_cancelled_customer'        => true,
        'email_toggle_status_cancelled_admin'           => false,
        'email_toggle_followup_customer'                => true,
        'email_toggle_new_inquiry_customer'             => true,
        'email_toggle_new_inquiry_admin'                => true,
    ];

    private array $templateDefaults = [
        'email_tpl_bconf_header_title'    => 'Booking Request Received',
        'email_tpl_bconf_header_subtitle' => "Cox's Bazar, Bangladesh",
        'email_tpl_bconf_intro_text'      => 'Thank you for choosing Hotel Beach Way! We have received your booking request and our team will review and confirm it within 24 hours.',
        'email_tpl_bconf_footer_text'     => "Near Kolatoli Beach, Cox's Bazar, Bangladesh",

        'email_tpl_bnotify_header_title'    => 'New Booking Request',
        'email_tpl_bnotify_header_subtitle' => 'Submitted via Hotel Beach Way website',
        'email_tpl_bnotify_footer_text'     => 'This notification was sent to the Hotel Beach Way admin team.',

        'email_tpl_bfollowup_header_subtitle' => "Cox's Bazar, Bangladesh",
        'email_tpl_bfollowup_footer_text'     => "Near Kolatoli Beach, Cox's Bazar, Bangladesh",

        'email_tpl_bstatus_footer_text' => "Near Kolatoli Beach, Cox's Bazar, Bangladesh",
    ];

    public function edit(): Response
    {
        $keys     = $this->allKeys();
        $settings = GlobalSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        foreach ($this->toggleDefaults as $key => $default) {
            $settings[$key] = array_key_exists($key, $settings) ? $settings[$key] === '1' : $default;
        }

        foreach ($this->allTemplateDefaults() as $key => $default) {
            $settings[$key] = $settings[$key] ?? $default;
            if ($settings[$key] === '') {
                $settings[$key] = $default;
            }
        }

        return Inertia::render('Admin/WebsiteSettings/Email/Edit', [
            'settings'       => $settings,
            'statusStatuses' => EmailNotificationSettings::EMAIL_STATUSES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys($this->toggleDefaults) as $key) {
            $rules[$key] = 'boolean';
        }
        foreach (array_keys($this->allTemplateDefaults()) as $key) {
            $rules[$key] = 'nullable|string';
        }

        $data = $request->validate($rules);

        foreach ($this->toggleDefaults as $key => $default) {
            $data[$key] = $request->boolean($key) ? '1' : '0';
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Email notification settings saved.');
    }

    private function allTemplateDefaults(): array
    {
        $defaults = $this->templateDefaults;

        foreach (EmailNotificationSettings::EMAIL_STATUSES as $status) {
            $defaults["email_tpl_bstatus_{$status}_title"] = EmailNotificationSettings::STATUS_TITLE_DEFAULTS[$status];
            $defaults["email_tpl_bstatus_{$status}_body"]  = EmailNotificationSettings::STATUS_BODY_DEFAULTS[$status];
        }

        return $defaults;
    }

    private function allKeys(): array
    {
        return array_merge(array_keys($this->toggleDefaults), array_keys($this->allTemplateDefaults()));
    }
}
