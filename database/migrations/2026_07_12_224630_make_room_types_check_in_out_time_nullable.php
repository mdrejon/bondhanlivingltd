<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE room_types MODIFY check_in_time VARCHAR(10) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE room_types MODIFY check_out_time VARCHAR(10) NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("UPDATE room_types SET check_in_time = '12:30' WHERE check_in_time IS NULL");
        DB::statement("UPDATE room_types SET check_out_time = '11:30' WHERE check_out_time IS NULL");
        DB::statement("ALTER TABLE room_types MODIFY check_in_time VARCHAR(10) NOT NULL DEFAULT '12:30'");
        DB::statement("ALTER TABLE room_types MODIFY check_out_time VARCHAR(10) NOT NULL DEFAULT '11:30'");
    }
};
