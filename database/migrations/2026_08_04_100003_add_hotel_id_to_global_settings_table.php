<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 8A — global_settings was a single flat key=>value table (unique(key)),
 * structurally incapable of holding more than one hotel's site config (About copy,
 * SMTP, header/footer, etc.). Adds hotel_id and switches the uniqueness to
 * (hotel_id, key) so every hotel gets its own row per setting key. Existing rows
 * belong to the current primary hotel (today's only tenant), backfilled directly —
 * no interleaved seeding step needed here, unlike the tenant-tables migration, so
 * this is done in one migration rather than split nullable/required.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('global_settings', function (Blueprint $t) {
            $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->restrictOnDelete();
        });

        $hotelId = DB::table('hotels')->where('is_primary_site', true)->value('id')
            ?? DB::table('hotels')->orderBy('id')->value('id');

        DB::table('global_settings')->update(['hotel_id' => $hotelId]);

        Schema::table('global_settings', function (Blueprint $t) {
            $t->dropUnique('global_settings_key_unique');
            $t->unique(['hotel_id', 'key']);
        });

        DB::statement('ALTER TABLE global_settings MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $t) {
            $t->dropUnique(['hotel_id', 'key']);
        });

        // Collapse back to one row per key (keep the lowest id per key) before
        // restoring the global unique(key) constraint.
        DB::statement('
            DELETE g1 FROM global_settings g1
            INNER JOIN global_settings g2
            WHERE g1.key = g2.key AND g1.id > g2.id
        ');

        Schema::table('global_settings', function (Blueprint $t) {
            $t->unique('key');
            $t->dropConstrainedForeignId('hotel_id');
        });
    }
};
