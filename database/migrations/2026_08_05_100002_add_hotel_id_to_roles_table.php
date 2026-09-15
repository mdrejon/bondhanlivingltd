<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 8C — delegated role management. Existing roles (Super Admin, DC/UNO/Police,
 * and any hotel-facing roles created before this phase — e.g. the "Hotel Admin"
 * role currently shared by two different hotels) deliberately keep hotel_id = null:
 * a null-hotel_id role is a shared/platform role, editable only by a Super Admin.
 * Splitting an already-in-use shared role per-hotel is a real data migration this
 * phase does not attempt — see docs/hgrm-saas plan for Phase 8C.
 *
 * Going forward, a role created BY a hotel-scoped admin (RoleController, once
 * user-management is granted to a hotel role) is stamped with their own hotel_id,
 * and they may only ever see/edit/delete roles matching it — never null (shared)
 * roles, never another hotel's roles.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $t) {
            $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $t) {
            $t->dropConstrainedForeignId('hotel_id');
        });
    }
};
