<?php

namespace App\Support;

use Illuminate\Http\Request;
use Throwable;

/**
 * Zero-friction bot detection for public forms: a honeypot field bots tend to
 * auto-fill, plus a signed render timestamp bots that skip page-load/JS skip too.
 * No CAPTCHA, no user interaction required.
 */
class SpamGuard
{
    protected const HONEYPOT_FIELD = 'company_website';
    protected const TIMESTAMP_FIELD = 'form_rendered_at';
    protected const MIN_SECONDS = 3;

    public static function isSpam(Request $request): bool
    {
        return self::reason($request) !== null;
    }

    /**
     * Same check as isSpam(), but returns *why* a submission was flagged so
     * blocked attempts can be diagnosed from the log line alone instead of
     * guessed at after the fact.
     */
    public static function reason(Request $request): ?string
    {
        if (filled($request->input(self::HONEYPOT_FIELD))) {
            return 'honeypot_filled';
        }

        $token = $request->input(self::TIMESTAMP_FIELD);

        if (! $token) {
            // Most often a stale cached page loaded before this field existed,
            // rather than an actual bot — worth a glance if this fires a lot.
            return 'missing_timestamp_token';
        }

        try {
            $renderedAt = (int) decrypt($token);
        } catch (Throwable) {
            return 'invalid_timestamp_token';
        }

        return (time() - $renderedAt) < self::MIN_SECONDS ? 'submitted_too_fast' : null;
    }
}
