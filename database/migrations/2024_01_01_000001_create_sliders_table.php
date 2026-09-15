<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();          // e.g. "Welcome To Hotel Beach Way"
            $table->string('title');                       // main heading
            $table->string('subtitle')->nullable();        // accent/colored words
            $table->text('description')->nullable();       // paragraph text
            $table->string('button_text')->default('Explore');
            $table->string('button_url')->default('#');
            $table->string('background_image')->nullable(); // stored path
            $table->string('star_label')->nullable();       // e.g. "3-Star Standard Hotel"
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
