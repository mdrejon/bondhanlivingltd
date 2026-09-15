<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\District;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Locks in the tenant/jurisdiction isolation mechanics from
 * docs/hgrm-saas/DATABASE-SCHEMA.md (App\Support\CurrentHotel + App\Support\HotelAccess +
 * App\Models\Concerns\BelongsToHotel) with real HTTP requests, not just Eloquent queries —
 * this is the "correctness requirement, not just UX" PRD § 6 calls for.
 *
 * Runs against the isolated `hotel-beach-way-testing` database (see .env.testing) — never
 * the real dev database. `2026_08_01_100007_add_hotel_id_to_tenant_tables.php` seeds
 * Bangladesh geography and creates a real "Hotel Beach Way" row (Cox's Bazar, marked
 * is_primary_site) as part of every fresh migration, which this test relies on as "Hotel A"
 * rather than re-creating it.
 */
class HotelTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotelA;    // Cox's Bazar — created by the migration's own backfill
    private Hotel $hotelA2;   // also Cox's Bazar — a second hotel in the same jurisdiction
    private Hotel $hotelB;    // Dhaka — a different jurisdiction entirely

    private Customer $customerA;
    private Customer $customerA2;
    private Customer $customerB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hotelA = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();

        $this->hotelA2 = Hotel::create([
            'name' => 'Second Coxsbazar Hotel', 'slug' => 'second-coxsbazar-hotel',
            'mobile' => '0177000001', 'address' => 'Cox\'s Bazar test address',
            'district_id' => $this->hotelA->district_id,
            'upazila_id' => $this->hotelA->upazila_id,
            'police_station_id' => $this->hotelA->police_station_id,
            'status' => 'active',
        ]);

        $dhaka = District::where('name', 'Dhaka')->firstOrFail();
        $this->hotelB = Hotel::create([
            'name' => 'Dhaka Test Hotel', 'slug' => 'dhaka-test-hotel',
            'mobile' => '0177000002', 'address' => 'Dhaka test address',
            'district_id' => $dhaka->id,
            'police_station_id' => PoliceStation::where('district_id', $dhaka->id)->firstOrFail()->id,
            'status' => 'active',
        ]);

        RoomType::create(['hotel_id' => $this->hotelA->id, 'name' => 'A Type', 'slug' => 'a-type']);
        RoomType::create(['hotel_id' => $this->hotelA2->id, 'name' => 'A2 Type', 'slug' => 'a2-type']);
        RoomType::create(['hotel_id' => $this->hotelB->id, 'name' => 'B Type', 'slug' => 'b-type']);

        $this->customerA  = Customer::create(['hotel_id' => $this->hotelA->id, 'name' => 'Guest A', 'phone' => '01710000001']);
        $this->customerA2 = Customer::create(['hotel_id' => $this->hotelA2->id, 'name' => 'Guest A2', 'phone' => '01710000002']);
        $this->customerB  = Customer::create(['hotel_id' => $this->hotelB->id, 'name' => 'Guest B', 'phone' => '01710000003']);
    }

    private function makeRole(string $scopeType, bool $superAdmin = false): Role
    {
        $role = Role::create([
            'name' => 'Test ' . $scopeType . ' ' . uniqid(),
            'slug' => 'test-' . $scopeType . '-' . uniqid(),
            'is_super_admin' => $superAdmin,
            'is_active' => true,
            'scope_type' => $scopeType,
        ]);

        foreach (['customers', 'dashboard', 'gov-reports'] as $module) {
            RolePermission::create([
                'role_id' => $role->id, 'module_key' => $module,
                'can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false,
            ]);
        }

        return $role;
    }

    private function makeUser(Role $role, array $jurisdiction = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User ' . uniqid(),
            'email' => uniqid() . '@hgrm-test.local',
            'password' => 'password',
            'role_id' => $role->id,
            'is_active' => true,
        ], $jurisdiction));
    }

    public function test_hotel_role_user_sees_only_their_own_hotel(): void
    {
        $user = $this->makeUser($this->makeRole('hotel'), ['hotel_id' => $this->hotelB->id]);

        $this->actingAs($user);

        $this->assertSame(1, RoomType::count());
        $this->assertSame($this->hotelB->id, RoomType::first()->hotel_id);
        $this->assertSame(1, Customer::count());
        $this->assertTrue(Customer::first()->is($this->customerB));
    }

    public function test_hotel_role_user_gets_404_for_another_hotels_customer(): void
    {
        $user = $this->makeUser($this->makeRole('hotel'), ['hotel_id' => $this->hotelB->id]);

        $this->actingAs($user)
            ->get(route('admin.customers.show', $this->customerA))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('admin.customers.show', $this->customerB))
            ->assertOk();
    }

    public function test_hotel_role_user_without_a_hotel_sees_nothing(): void
    {
        $user = $this->makeUser($this->makeRole('hotel')); // no hotel_id — misconfigured

        $this->actingAs($user);

        $this->assertSame(0, Customer::count());
        $this->assertSame(0, RoomType::count());
    }

    public function test_district_scoped_government_user_sees_every_hotel_in_their_district(): void
    {
        $dc = $this->makeUser($this->makeRole('district'), ['district_id' => $this->hotelA->district_id]);

        $this->actingAs($dc);

        $this->assertSame(2, Customer::count()); // customerA + customerA2, both in Cox's Bazar
        $this->assertEqualsCanonicalizing(
            [$this->customerA->id, $this->customerA2->id],
            Customer::pluck('id')->all()
        );
    }

    public function test_district_scoped_government_user_cannot_see_another_districts_customer(): void
    {
        $dc = $this->makeUser($this->makeRole('district'), ['district_id' => $this->hotelA->district_id]);

        $this->actingAs($dc)
            ->get(route('admin.customers.show', $this->customerB))
            ->assertNotFound();

        $this->actingAs($dc)
            ->get(route('admin.customers.show', $this->customerA))
            ->assertOk();
    }

    public function test_upazila_scoped_government_user_is_scoped_to_their_upazila(): void
    {
        $uno = $this->makeUser($this->makeRole('upazila'), ['upazila_id' => $this->hotelA->upazila_id]);

        $this->actingAs($uno);

        $this->assertEqualsCanonicalizing(
            [$this->customerA->id, $this->customerA2->id],
            Customer::pluck('id')->all()
        );
    }

    public function test_police_station_scoped_government_user_is_scoped_to_their_thana(): void
    {
        $police = $this->makeUser($this->makeRole('police_station'), ['police_station_id' => $this->hotelA->police_station_id]);

        $this->actingAs($police);

        $this->assertEqualsCanonicalizing(
            [$this->customerA->id, $this->customerA2->id],
            Customer::pluck('id')->all()
        );
    }

    public function test_super_admin_sees_every_hotel(): void
    {
        $admin = $this->makeUser($this->makeRole('platform', superAdmin: true));

        $this->actingAs($admin);

        $this->assertSame(3, Customer::count());
        $this->assertSame(3, RoomType::count());
    }

    public function test_unauthenticated_request_is_scoped_to_the_primary_site_hotel(): void
    {
        // No actingAs() — simulates the public marketing website.
        $this->assertSame(1, RoomType::count());
        $this->assertSame($this->hotelA->id, RoomType::first()->hotel_id);
    }

    public function test_explicit_hotel_assignment_overrides_jurisdiction_match(): void
    {
        $dc = $this->makeUser($this->makeRole('district'), ['district_id' => District::where('name', 'Dhaka')->value('id')]);

        // Without an assignment, this Dhaka DC only sees Hotel B.
        $this->actingAs($dc);
        $this->assertEqualsCanonicalizing([$this->customerB->id], Customer::pluck('id')->all());

        // Explicitly assign them to Hotel A instead — assignment REPLACES the jurisdiction
        // match, it doesn't add to it (see docs/hgrm-saas/DATABASE-SCHEMA.md).
        DB::table('hotel_user_assignments')->insert([
            'hotel_id' => $this->hotelA->id, 'user_id' => $dc->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->assertEqualsCanonicalizing([$this->customerA->id], Customer::pluck('id')->all());
    }
}
