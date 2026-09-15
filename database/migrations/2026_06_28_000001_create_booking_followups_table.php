<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('room_bookings')->onDelete('cascade');
            $table->enum('call_status', ['pending', 'called', 'no_answer', 'callback_requested'])->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('follow_up_at')->nullable();
            $table->string('operator_name', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_followups');
    }
};
