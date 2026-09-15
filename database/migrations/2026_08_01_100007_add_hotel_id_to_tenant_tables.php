<?php

use Database\Seeders\BdGeographySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Turns the system multi-tenant: adds a nullable, FK-constrained hotel_id to every
 * tenant-owned table, then creates "Hotel Beach Way" as Tenant #1 from its real
 * existing site settings + the Bangladesh geography seeded in Phase 1, and backfills
 * every existing row onto it. hotel_id stays nullable here on purpose — a follow-up
 * migration makes it NOT NULL once this backfill has run (see
 * 2026_08_01_100008_make_hotel_id_required_on_tenant_tables.php).
 *
 * users.hotel_id is backfilled too (every existing user is, today, Hotel Beach Way
 * staff) but is left nullable permanently — it's null for future platform/government
 * accounts that aren't tied to one hotel.
 *
 * Explicitly seeds Bangladesh geography here (idempotent — BdGeographySeeder skips
 * per-table if already populated) rather than assuming it. `php artisan migrate` alone
 * (no `--seed`) — which is exactly what a fresh install, CI, and RefreshDatabase-based
 * tests all do — would otherwise leave districts/upazilas/police_stations empty and
 * this migration's district/police-station lookup would resolve to null, violating the
 * NOT NULL constraint on `hotels`. Found via a RefreshDatabase test run against a fresh
 * database, not assumed.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['room_types', 'rooms', 'customers', 'room_bookings', 'inquiries'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->restrictOnDelete();
            });
        }

        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->nullOnDelete();
        });

        Artisan::call('db:seed', ['--class' => BdGeographySeeder::class, '--force' => true]);

        $districtId       = DB::table('districts')->where('name', 'Coxsbazar')->value('id');
        $upazilaId        = DB::table('upazilas')->where('district_id', $districtId)->where('name', 'Coxsbazar Sadar')->value('id');
        $policeStationId  = DB::table('police_stations')->where('upazila_id', $upazilaId)->value('id');

        $setting = fn (string $key, ?string $default = null) => DB::table('global_settings')->where('key', $key)->value('value') ?? $default;

        $address = trim(implode(', ', array_filter([
            $setting('footer_address_line1'),
            $setting('footer_address_line2'),
        ]))) ?: $setting('header_address', 'Cox\'s Bazar, Bangladesh');

        $now = now();

        $hotelId = DB::table('hotels')->insertGetId([
            'name'             => $setting('mail_from_name', 'Hotel Beach Way'),
            'slug'             => 'hotel-beach-way',
            'mobile'           => $setting('header_phone') ?? $setting('footer_phone_1', ''),
            'email'            => $setting('header_email') ?? $setting('footer_email_1'),
            'address'          => $address,
            'district_id'      => $districtId,
            'upazila_id'       => $upazilaId,
            'police_station_id'=> $policeStationId,
            'total_rooms'      => DB::table('rooms')->count(),
            'is_primary_site'  => true,
            'status'           => 'active',
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        foreach (['room_types', 'rooms', 'customers', 'room_bookings', 'inquiries', 'users'] as $table) {
            DB::table($table)->update(['hotel_id' => $hotelId]);
        }

        // customers.phone was globally unique under single-tenant. Guests are per-hotel
        // now (see docs/hgrm-saas README decision #3) — the same phone number must be
        // insertable once per hotel, not once system-wide.
        Schema::table('customers', function (Blueprint $t) {
            $t->dropUnique('customers_phone_unique');
            $t->unique(['hotel_id', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $t) {
            $t->dropUnique(['hotel_id', 'phone']);
            $t->unique('phone');
        });

        foreach (['room_types', 'rooms', 'customers', 'room_bookings', 'inquiries', 'users'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('hotel_id');
            });
        }
    }
};
