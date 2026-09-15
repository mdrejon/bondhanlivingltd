<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('price');
            $table->string('discount_type_bdt', 12)->nullable()->after('price_usd');  // 'percentage' | 'fixed'
            $table->decimal('discount_value_bdt', 10, 2)->nullable()->after('discount_type_bdt');
            $table->string('discount_type_usd', 12)->nullable()->after('discount_value_bdt');
            $table->decimal('discount_value_usd', 10, 2)->nullable()->after('discount_type_usd');
            $table->dateTime('offer_expires_at')->nullable()->after('discount_value_usd');
        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn([
                'price_usd',
                'discount_type_bdt',
                'discount_value_bdt',
                'discount_type_usd',
                'discount_value_usd',
                'offer_expires_at',
            ]);
        });
    }
};
