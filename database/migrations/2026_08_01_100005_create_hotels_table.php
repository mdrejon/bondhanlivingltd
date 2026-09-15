<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Legal registration numbers are nullable: at onboarding time (including the
            // Tenant #1 backfill in the next migration) these often aren't on hand yet and
            // get filled in later via the hotel edit form — not something to fabricate.
            $table->string('trade_license_no')->nullable()->unique();
            $table->string('bin_no')->nullable()->unique();
            $table->string('tin_no')->nullable()->unique();
            $table->string('owner_name')->nullable();
            $table->string('owner_nid')->nullable();

            $table->string('mobile');
            $table->string('email')->nullable();
            $table->text('address');

            $table->foreignId('district_id')->constrained('districts')->restrictOnDelete();
            $table->foreignId('upazila_id')->nullable()->constrained('upazilas')->nullOnDelete();
            $table->foreignId('police_station_id')->constrained('police_stations')->restrictOnDelete();

            // No single Bangladesh hotel-classification standard is confirmed yet
            // (see docs/hgrm-saas/README.md open questions) — free string until decided.
            $table->string('category')->nullable();
            $table->unsignedInteger('total_rooms')->default(0);
            $table->string('logo')->nullable();

            // The one hotel the public marketing website currently represents — the public
            // frontend isn't multi-domain yet, so exactly one hotel is flagged primary and
            // used to resolve tenant scope for unauthenticated requests (see App\Support\CurrentHotel).
            $table->boolean('is_primary_site')->default(false);

            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
