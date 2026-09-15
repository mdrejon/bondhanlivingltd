<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use App\Support\CurrentHotel;
use App\Support\EmailNotificationSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class MailSettingController extends Controller
{
    private array $keys = [
        'mail_enabled',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_encryption',
        'mail_username',
        'mail_password',
        'mail_from_address',
        'mail_from_name',
        'mail_admin_emails',
    ];

    public function edit(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->keys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->keys as $key) {
            $settings[$key] ??= null;
        }

        $settings['mail_admin_emails'] = $settings['mail_admin_emails']
            ? json_decode($settings['mail_admin_emails'], true)
            : [];

        return Inertia::render('Admin/WebsiteSettings/Mail/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mail_enabled'      => 'boolean',
            'mail_driver'       => 'required|in:smtp,sendmail,log',
            'mail_host'         => 'nullable|string',
            'mail_port'         => 'nullable|integer',
            'mail_encryption'   => 'nullable|in:tls,ssl,starttls,',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name'    => 'nullable|string',
            'mail_admin_emails'   => 'nullable|array',
            'mail_admin_emails.*' => 'email',
        ]);

        // Don't overwrite password if left blank
        if (empty($data['mail_password'])) {
            unset($data['mail_password']);
        }

        $data['mail_enabled']     = $request->boolean('mail_enabled') ? '1' : '0';
        $data['mail_admin_emails'] = json_encode(
            array_values(array_filter($data['mail_admin_emails'] ?? []))
        );

        GlobalSetting::setMany($data);

        return back()->with('success', 'Mail settings saved.');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        // Apply current DB mail config (for the hotel this admin is managing) before sending
        EmailNotificationSettings::applyMailConfigFor(CurrentHotel::homeId());

        try {
            Mail::raw(
                "This is a test email from Hotel Beach Way admin panel.\n\nIf you received this, your SMTP configuration is working correctly.",
                function ($msg) use ($request) {
                    $fromAddress = GlobalSetting::get('mail_from_address') ?? config('mail.from.address');
                    $fromName    = GlobalSetting::get('mail_from_name')    ?? config('mail.from.name');

                    $msg->to($request->test_email)
                        ->from($fromAddress, $fromName)
                        ->subject('Test Email — Hotel Beach Way');
                }
            );

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
