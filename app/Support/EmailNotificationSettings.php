<?php

namespace App\Support;

use App\Models\GlobalSetting;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationSettings
{
    /** Statuses that trigger a customer/admin BookingStatusMail (pending and no_show excluded). */
    public const EMAIL_STATUSES = ['confirmed', 'payment_pending', 'checked_in', 'checked_out', 'cancelled'];

    public const STATUS_TITLE_DEFAULTS = [
        'confirmed'       => 'Your Booking is Confirmed!',
        'payment_pending' => 'Payment Required',
        'checked_in'      => 'Welcome to Hotel Beach Way!',
        'checked_out'     => 'Thank You for Your Stay',
        'cancelled'       => 'Booking Cancelled',
    ];

    public const STATUS_BODY_DEFAULTS = [
        'confirmed'       => 'Great news! Your booking has been reviewed and <strong>confirmed</strong> by our team. We look forward to welcoming you to Hotel Beach Way.',
        'payment_pending' => 'Your booking is reserved, but we are awaiting your <strong>payment</strong> to fully confirm it. Please complete your payment at your earliest convenience to secure your room.',
        'checked_in'      => 'You are now officially <strong>checked in</strong> at Hotel Beach Way. We hope you enjoy every moment of your stay with us!',
        'checked_out'     => 'You have been <strong>checked out</strong> from Hotel Beach Way. Thank you for choosing us — we hope to see you again soon!',
        'cancelled'       => 'We regret to inform you that your booking has been <strong>cancelled</strong>. If you have any questions, please contact our team.',
    ];

    /**
     * Read a checkbox toggle stored as '1'/'0' in global_settings. Falls back to
     * $default when the admin has never saved this key yet.
     */
    public static function enabled(string $key, bool $default): bool
    {
        $value = GlobalSetting::get($key);

        return $value === null ? $default : $value === '1';
    }

    /**
     * Admin recipient list, sourced from the existing Mail Settings "Admin Receiver
     * Emails" field so it isn't duplicated on the notifications page. Falls back to
     * ADMIN_EMAIL / mail.from.address if the admin never configured that field.
     */
    public static function adminRecipients(): array
    {
        $raw    = GlobalSetting::get('mail_admin_emails');
        $emails = $raw ? (json_decode($raw, true) ?: []) : [];
        $emails = array_values(array_filter($emails));

        return $emails ?: array_filter([env('ADMIN_EMAIL', config('mail.from.address'))]);
    }

    /**
     * Sends a fresh mailable (built per-recipient by $factory) to each admin recipient
     * as its own separate message, rather than one email with every admin listed in the
     * To: header. This keeps admin addresses private from each other and stops one
     * recipient's delivery problem (bounce, spam filter, etc.) from being silently
     * bundled with the rest — each attempt is logged independently.
     *
     * A factory (not a shared Mailable instance) is required because Mailable::to()
     * appends to its internal recipient list rather than replacing it — reusing one
     * instance across a loop of Mail::to($email)->send($mailable) calls would leave
     * every message addressed to all recipients, not just the current one.
     *
     * @param \Closure(): Mailable $factory
     */
    public static function sendToAdmins(\Closure $factory, string $logContext = 'Admin'): void
    {
        foreach (self::adminRecipients() as $email) {
            try {
                Mail::to($email)->send($factory());
            } catch (\Throwable $e) {
                Log::error("{$logContext} email failed for {$email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Admin-editable static template string, falling back to the given default
     * (the copy that shipped in the blade view) when never customized.
     */
    public static function template(string $key, string $default): string
    {
        $value = GlobalSetting::get($key);

        return $value !== null && $value !== '' ? $value : $default;
    }

    /**
     * Applies one specific hotel's SMTP config to the mail.* config at runtime.
     * Bypasses GlobalSetting's ambient hotel scope on purpose — a transactional
     * email (booking/inquiry confirmation) must go out via the hotel that OWNS
     * that record, not whichever hotel happened to be ambient when this request
     * booted (see AppServiceProvider::bootMailConfig(), which sets a process-wide
     * default before any request-specific hotel context exists) or whichever
     * hotel the sending admin happens to be acting as.
     */
    public static function applyMailConfigFor(?int $hotelId): void
    {
        if (!$hotelId) {
            return;
        }

        $settings = GlobalSetting::withoutGlobalScope('hotel')
            ->where('hotel_id', $hotelId)
            ->whereIn('key', [
                'mail_enabled', 'mail_driver', 'mail_host', 'mail_port',
                'mail_encryption', 'mail_username', 'mail_password',
                'mail_from_address', 'mail_from_name',
            ])->pluck('value', 'key');

        if ($settings->get('mail_enabled') !== '1') {
            return;
        }

        config([
            'mail.default'                 => $settings->get('mail_driver', 'smtp'),
            'mail.mailers.smtp.host'       => $settings->get('mail_host'),
            'mail.mailers.smtp.port'       => (int) ($settings->get('mail_port') ?: 587),
            'mail.mailers.smtp.encryption' => $settings->get('mail_encryption') ?: null,
            'mail.mailers.smtp.username'   => $settings->get('mail_username'),
            'mail.mailers.smtp.password'   => $settings->get('mail_password'),
            'mail.from.address'            => $settings->get('mail_from_address') ?: config('mail.from.address'),
            'mail.from.name'               => $settings->get('mail_from_name')    ?: config('mail.from.name'),
        ]);
    }
}
