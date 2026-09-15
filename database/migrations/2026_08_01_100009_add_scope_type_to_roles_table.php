<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drives which column on `users` App\Support\HotelAccess reads to compute a
            // non-super-admin user's visible hotels. `is_super_admin` stays the
            // unconditional bypass exactly as before — this only matters otherwise.
            $table->enum('scope_type', ['platform', 'hotel', 'district', 'upazila', 'police_station'])
                ->default('hotel')
                ->after('is_super_admin');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('scope_type');
        });
    }
};
