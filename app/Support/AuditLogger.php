<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Compliance audit trail (PRD § 6) for a system whose whole point is giving
 * government accounts cross-hotel visibility into citizen PII. Deliberately only
 * logs super-admin/government-scoped access, not routine hotel-staff operations —
 * a receptionist viewing a guest they themselves checked in isn't an oversight
 * concern; a DC/UNO/Police account (or platform admin) viewing/exporting guest data
 * across hotels they don't operate is exactly what needs a trail.
 */
class AuditLogger
{
    public static function log(string $action, ?Model $subject = null, array $meta = []): void
    {
        if (!self::shouldLog()) {
            return;
        }

        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'meta'         => $meta,
        ]);
    }

    private static function shouldLog(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return true;
        }

        return in_array($user->role?->scope_type, ['district', 'upazila', 'police_station'], true);
    }
}
