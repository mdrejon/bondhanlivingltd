<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('room_bookings')->onDelete('cascade');
            $table->string('action', 60);
            $table->text('description');
            $table->string('old_value', 100)->nullable();
            $table->string('new_value', 100)->nullable();
            $table->string('performed_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};
