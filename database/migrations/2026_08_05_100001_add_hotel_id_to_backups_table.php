<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 8B — per-hotel backup/export. Existing rows are full-database backups
 * created by a Super Admin (via the unchanged, unconditional whole-DB
 * DatabaseBackupService) — hotel_id stays null for those. A hotel-scoped backup
 * (via the new HotelDataBackupService/HotelBackupController) always has hotel_id
 * set, so a hotel admin's backup list/actions can be scoped to just their own.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backups', function (Blueprint $t) {
            $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('backups', function (Blueprint $t) {
            $t->dropConstrainedForeignId('hotel_id');
        });
    }
};
