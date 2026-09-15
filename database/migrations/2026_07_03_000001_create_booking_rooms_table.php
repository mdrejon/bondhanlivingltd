<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_booking_id')->constrained('room_bookings')->cascadeOnDelete();
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedSmallInteger('nights')->default(1);
            $table->decimal('price_per_night', 10, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'payment_pending', 'checked_in', 'checked_out', 'no_show', 'cancelled'])->default('pending');
            $table->timestamp('actual_check_in_at')->nullable();
            $table->timestamp('actual_check_out_at')->nullable();
            $table->timestamps();
        });

        // room_bookings.price_per_night is no longer set at the header level once a single
        // booking can span several room types/prices — each line now snapshots its own price.
        DB::statement('ALTER TABLE room_bookings MODIFY COLUMN price_per_night DECIMAL(10,2) NULL');

        // Backfill: every existing booking becomes a single-line booking_rooms row so historical
        // data keeps working unchanged. The now-redundant columns on room_bookings are dropped
        // later, once the application code running against the new table has been verified.
        $now = now();
        DB::table('room_bookings')->orderBy('id')->chunkById(200, function ($bookings) use ($now) {
            $rows = [];
            foreach ($bookings as $booking) {
                $rows[] = [
                    'room_booking_id'     => $booking->id,
                    'room_type_id'        => $booking->room_type_id,
                    'room_id'             => $booking->room_id,
                    'adults'              => $booking->adults,
                    'children'            => $booking->children,
                    'nights'              => $booking->total_nights,
                    'price_per_night'     => $booking->price_per_night ?? 0,
                    'line_total'          => $booking->total_amount,
                    'status'              => $booking->booking_status,
                    'actual_check_in_at'  => $booking->actual_check_in_at,
                    'actual_check_out_at' => $booking->actual_check_out_at,
                    'created_at'          => $booking->created_at ?? $now,
                    'updated_at'          => $booking->updated_at ?? $now,
                ];
            }
            DB::table('booking_rooms')->insert($rows);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_rooms');
    }
};
