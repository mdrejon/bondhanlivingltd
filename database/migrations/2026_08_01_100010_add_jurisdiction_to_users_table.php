<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Set for government-role users matching their role's scope_type (district for
            // DC Office, upazila for UNO Office, police_station for Police Admin). Null for
            // hotel-role and platform accounts. See App\Support\HotelAccess.
            $table->foreignId('district_id')->nullable()->after('hotel_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('upazila_id')->nullable()->after('district_id')->constrained('upazilas')->nullOnDelete();
            $table->foreignId('police_station_id')->nullable()->after('upazila_id')->constrained('police_stations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('upazila_id');
            $table->dropConstrainedForeignId('police_station_id');
        });
    }
};
