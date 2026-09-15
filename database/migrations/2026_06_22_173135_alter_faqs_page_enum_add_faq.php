<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE faqs MODIFY COLUMN page ENUM('home','about','faq') NOT NULL DEFAULT 'home'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE faqs MODIFY COLUMN page ENUM('home','about') NOT NULL DEFAULT 'home'");
    }
};
