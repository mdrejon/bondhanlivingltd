<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->enum('page', ['home', 'about'])->default('home')->index();
            $table->string('badge', 100)->default('FAQS');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->json('items');           // [{"question":"...","answer":"..."}, ...]
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
