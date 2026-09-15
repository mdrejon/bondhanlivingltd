<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['room_type_id']);
            $table->dropColumn(['room_id', 'room_type_id', 'price_per_night', 'actual_check_in_at', 'actual_check_out_at']);
        });
    }

    public function down(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->after('customer_id')->constrained('rooms')->nullOnDelete();
            $table->foreignId('room_type_id')->nullable()->after('room_id')->constrained('room_types')->nullOnDelete();
            $table->decimal('price_per_night', 10, 2)->nullable()->after('total_nights');
            $table->timestamp('actual_check_in_at')->nullable()->after('check_out_date');
            $table->timestamp('actual_check_out_at')->nullable()->after('actual_check_in_at');
        });
    }
};
