<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('feature_image')->nullable()->after('gallery_images');
            $table->string('meta_title', 160)->nullable()->after('feature_image');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
            $table->string('meta_keywords', 500)->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['feature_image', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });
    }
};
