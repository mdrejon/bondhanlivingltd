<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_followups', function (Blueprint $table) {
            $table->dropColumn(['call_status', 'notes', 'follow_up_at', 'operator_name']);
            $table->string('title', 200)->after('booking_id');
            $table->text('description')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('booking_followups', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
            $table->enum('call_status', ['pending', 'called', 'no_answer', 'callback_requested'])->default('pending')->after('booking_id');
            $table->text('notes')->nullable()->after('call_status');
            $table->dateTime('follow_up_at')->nullable()->after('notes');
            $table->string('operator_name', 100)->nullable()->after('follow_up_at');
        });
    }
};
