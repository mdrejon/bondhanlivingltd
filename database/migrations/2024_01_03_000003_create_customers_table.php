<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30)->unique();
            $table->string('email', 150)->nullable();
            $table->string('nationality', 100)->nullable()->default('Bangladeshi');
            $table->string('address')->nullable();
            $table->enum('document_type', ['nid', 'passport', 'other'])->default('nid');
            $table->string('nid_number', 30)->nullable();
            $table->string('passport_number', 30)->nullable();
            $table->string('document_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
