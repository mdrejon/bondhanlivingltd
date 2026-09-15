<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tightens hotel_id to NOT NULL on the content tables now that the previous
 * migration has backfilled every row. Raw MODIFY statements (not
 * Schema::table()->change()) to avoid a doctrine/dbal dependency, matching the
 * existing project convention (see
 * 2026_08_01_100008_make_hotel_id_required_on_tenant_tables.php).
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
            DB::statement("ALTER TABLE {$table} MODIFY COLUMN hotel_id BIGINT UNSIGNED NOT NULL");
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE {$table} MODIFY COLUMN hotel_id BIGINT UNSIGNED NULL");
        }
    }
};
