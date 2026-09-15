<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reserves the theme-selection field for each hotel's public site. Only one theme
 * ("default" — today's existing design) exists right now; FrontendController doesn't
 * branch on this yet. This is data-model readiness for a future multi-theme system,
 * not the rendering engine itself (see docs/hgrm-saas plan for Phase 8A).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $t) {
            $t->string('template')->default('default')->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $t) {
            $t->dropColumn('template');
        });
    }
};
