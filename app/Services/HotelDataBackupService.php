<?php

namespace App\Services;

use App\Exceptions\HotelBackupStaleDataException;
use App\Models\Customer;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Per-hotel data export/import (Phase 8B) — deliberately NOT a filtered version of
 * DatabaseBackupService. That service does DROP TABLE + CREATE TABLE + full-table
 * INSERT per table, correct for a whole-database snapshot but wrong here: dropping/
 * recreating a shared table (e.g. `customers`) would destroy every OTHER hotel's
 * rows too. This service is data-only — SELECT/DELETE/INSERT scoped to one hotel,
 * never touching table structure, and JSON-formatted (not .sql) so per-table
 * filtering and the staleness check are trivial and hotel backups are visually
 * distinct from full-DB backups.
 *
 * users is included in the export, hashed passwords and all — the existing
 * whole-database .sql backup already includes the users table in plaintext SQL, so
 * this carries no new risk beyond what that established feature already accepts.
 *
 * Deliberately excluded: roles/role_permissions (not hotel-owned data to freely
 * overwrite via restore — see Phase 8C) and audit_logs (compliance trail, must not
 * be tamperable via a hotel admin's own restore).
 */
class HotelDataBackupService
{
    /** Tables with a direct hotel_id column. */
    private const DIRECT_TABLES = [
        'room_types', 'rooms', 'customers', 'room_bookings', 'inquiries',
        'services', 'faqs', 'gallery_images', 'testimonials', 'facilities',
        'sliders', 'blogs', 'blog_categories', 'room_amenities', 'global_settings', 'users',
    ];

    /** table => [parent table, this table's FK column pointing at parent.id]. */
    private const JOIN_TABLES = [
        'booking_rooms'     => ['room_bookings', 'room_booking_id'],
        'blog_comments'     => ['blogs', 'blog_id'],
        'room_type_amenity' => ['room_types', 'room_type_id'],
    ];

    /** No timestamp columns — included in dump/restore, skipped in the staleness check. */
    private const NO_TIMESTAMP_TABLES = ['room_type_amenity'];

    private const INSERT_CHUNK = 500;

    /** Dump the given hotel's data to a JSON file at the given absolute path. */
    public function dump(int $hotelId, string $absolutePath): void
    {
        $tables = [];
        foreach ($this->allTables() as $table) {
            $rows = $this->fetchRows($hotelId, $table);
            if ($rows) {
                $tables[$table] = $rows;
            }
        }

        $payload = [
            'format'                 => 'hotel-backup-v1',
            'hotel_id'               => $hotelId,
            'hotel_name'             => Hotel::withoutGlobalScopes()->find($hotelId)?->name,
            'generated_at'           => now()->toDateTimeString(),
            'snapshot_max_updated_at' => $this->maxUpdatedAt($hotelId),
            'tables'                 => $tables,
        ];

        if (file_put_contents($absolutePath, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
            throw new RuntimeException("Cannot write backup file: {$absolutePath}");
        }
    }

    /**
     * Restore the given hotel's data from a previously-dumped file. Refuses a file
     * that isn't hotel-backup-v1 format, or that was exported for a different
     * hotel. Refuses (unless $force) when the hotel has data newer than the
     * backup's snapshot.
     *
     * @throws HotelBackupStaleDataException
     */
    public function restore(int $hotelId, string $absolutePath, bool $force = false): void
    {
        $raw = file_get_contents($absolutePath);
        if ($raw === false) {
            throw new RuntimeException("Cannot read backup file: {$absolutePath}");
        }

        $payload = json_decode($raw, true);
        if (!is_array($payload) || ($payload['format'] ?? null) !== 'hotel-backup-v1') {
            throw new RuntimeException('This file is not a hotel backup created by this application (wrong format — a full-database backup cannot be restored here).');
        }

        if ((int) ($payload['hotel_id'] ?? 0) !== $hotelId) {
            throw new RuntimeException('This backup belongs to a different hotel and cannot be restored here.');
        }

        $snapshotAt = $payload['snapshot_max_updated_at'] ?? null;
        $currentAt  = $this->maxUpdatedAt($hotelId);

        if (!$force && $currentAt !== null && $snapshotAt !== null && $currentAt > $snapshotAt) {
            throw new HotelBackupStaleDataException($currentAt, $snapshotAt);
        }

        $tables = $payload['tables'] ?? [];

        DB::transaction(function () use ($hotelId, $tables) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                foreach ($this->allTables() as $table) {
                    $this->deleteRows($hotelId, $table);
                }

                foreach ($this->allTables() as $table) {
                    if (!empty($tables[$table]) && is_array($tables[$table])) {
                        $this->insertRows($table, $tables[$table]);
                    }
                }
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        });
    }

    /** The latest updated_at across every timestamped table scoped to this hotel, or null if empty. */
    public function maxUpdatedAt(int $hotelId): ?string
    {
        $max = null;

        foreach ($this->allTables() as $table) {
            if (in_array($table, self::NO_TIMESTAMP_TABLES, true)) {
                continue;
            }

            $tableMax = $this->maxUpdatedAtFor($hotelId, $table);
            if ($tableMax !== null && ($max === null || $tableMax > $max)) {
                $max = $tableMax;
            }
        }

        return $max;
    }

    private function allTables(): array
    {
        return [...self::DIRECT_TABLES, ...array_keys(self::JOIN_TABLES), 'documents'];
    }

    private function fetchRows(int $hotelId, string $table): array
    {
        return $this->scopedQuery($hotelId, $table)->get()->map(fn ($row) => (array) $row)->all();
    }

    private function deleteRows(int $hotelId, string $table): void
    {
        $this->scopedQuery($hotelId, $table)->delete();
    }

    private function maxUpdatedAtFor(int $hotelId, string $table): ?string
    {
        return $this->scopedQuery($hotelId, $table)->max($table === 'documents' ? 'documents.updated_at' : "{$table}.updated_at");
    }

    /** A query builder already scoped to this hotel's rows of the given table, whichever resolution strategy it needs. */
    private function scopedQuery(int $hotelId, string $table)
    {
        if ($table === 'documents') {
            return DB::table('documents')->where(function ($q) use ($hotelId) {
                $q->where(function ($q2) use ($hotelId) {
                    $q2->where('documentable_type', Hotel::class)->where('documentable_id', $hotelId);
                })->orWhere(function ($q2) use ($hotelId) {
                    $q2->where('documentable_type', Customer::class)
                        ->whereIn('documentable_id', DB::table('customers')->where('hotel_id', $hotelId)->select('id'));
                });
            });
        }

        if (isset(self::JOIN_TABLES[$table])) {
            [$parent, $fk] = self::JOIN_TABLES[$table];

            return DB::table($table)->whereIn($fk, DB::table($parent)->where('hotel_id', $hotelId)->select('id'));
        }

        return DB::table($table)->where('hotel_id', $hotelId);
    }

    private function insertRows(string $table, array $rows): void
    {
        foreach (array_chunk($rows, self::INSERT_CHUNK) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }
}
