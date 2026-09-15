<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 8A — multi-tenant frontend websites. Extends the hotel_id scoping already
 * applied to room_types/rooms/customers/room_bookings/inquiries (see
 * 2026_08_01_100007_add_hotel_id_to_tenant_tables.php) to the public-website content
 * models: today these are all single flat tables shared by every hotel, which is fine
 * for one hotel but would leak every hotel's About/Services/Gallery/FAQs/Blog into
 * every other hotel's site once a second hotel goes live at /hotel/{slug}.
 *
 * Same two-step convention as the earlier migration: nullable + backfill here,
 * NOT NULL in the follow-up migration once the backfill has run.
 */
return new class extends Migration
{
    private array $tables = [
        'services', 'faqs', 'gallery_images', 'testimonials',
        'facilities', 'sliders', 'blogs', 'blog_categories', 'room_amenities',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->restrictOnDelete();
            });
        }

        $hotelId = DB::table('hotels')->where('is_primary_site', true)->value('id')
            ?? DB::table('hotels')->orderBy('id')->value('id');

        foreach ($this->tables as $table) {
            DB::table($table)->update(['hotel_id' => $hotelId]);
        }

        // slug uniqueness was global under single-tenant; each hotel needs its own
        // slug namespace now (same reasoning as customers.phone in the earlier
        // migration).
        Schema::table('services', function (Blueprint $t) {
            $t->dropUnique('services_slug_unique');
            $t->unique(['hotel_id', 'slug']);
        });
        Schema::table('blogs', function (Blueprint $t) {
            $t->dropUnique('blogs_slug_unique');
            $t->unique(['hotel_id', 'slug']);
        });
        Schema::table('blog_categories', function (Blueprint $t) {
            $t->dropUnique('blog_categories_slug_unique');
            $t->unique(['hotel_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $t) {
            $t->dropUnique(['hotel_id', 'slug']);
            $t->unique('slug');
        });
        Schema::table('blogs', function (Blueprint $t) {
            $t->dropUnique(['hotel_id', 'slug']);
            $t->unique('slug');
        });
        Schema::table('blog_categories', function (Blueprint $t) {
            $t->dropUnique(['hotel_id', 'slug']);
            $t->unique('slug');
        });

        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('hotel_id');
            });
        }
    }
};
