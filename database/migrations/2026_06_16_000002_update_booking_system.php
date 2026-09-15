<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make customers.phone nullable (web bookings may not have phone)
        DB::statement('ALTER TABLE customers MODIFY COLUMN phone VARCHAR(30) NULL');

        // Make room_id nullable (web bookings get room assigned at check-in)
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN room_id BIGINT UNSIGNED NULL');

        // Make room_type_id nullable (general inquiries without specific room)
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN room_type_id BIGINT UNSIGNED NULL');

        // Expand booking_status enum with payment_pending and no_show
        DB::statement("ALTER TABLE room_bookings MODIFY COLUMN booking_status
            ENUM('pending','confirmed','payment_pending','checked_in','checked_out','no_show','cancelled')
            DEFAULT 'pending'");

        // Add source column to track web vs admin bookings
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->string('source', 20)->default('admin')->after('booked_by');
        });
    }

    public function down(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn('source');
        });

        DB::statement("ALTER TABLE room_bookings MODIFY COLUMN booking_status
            ENUM('pending','confirmed','checked_in','checked_out','cancelled')
            DEFAULT 'pending'");

        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN room_type_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN room_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE customers MODIFY COLUMN phone VARCHAR(30) NOT NULL');
    }
};
