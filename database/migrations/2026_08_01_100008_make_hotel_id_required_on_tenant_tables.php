<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tightens hotel_id to NOT NULL now that the previous migration has backfilled every
 * row. users.hotel_id is deliberately left nullable (platform/government accounts
 * aren't tied to one hotel) — see docs/hgrm-saas/DATABASE-SCHEMA.md.
 *
 * Raw MODIFY statements (not Schema::table()->change()) to avoid a doctrine/dbal
 * dependency, matching the existing project convention
 * (see 2026_07_12_224630_make_room_types_check_in_out_time_nullable.php).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE room_types MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE rooms MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE customers MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE inquiries MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE room_types MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE rooms MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE customers MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE inquiries MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL');
    }
};
