<?php

namespace Tests\Feature;

use App\Models\BookingRoom;
use App\Models\Customer;
use App\Models\District;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 6 — Monitoring Reports & dashboards. The core claim being tested is that
 * every report/dashboard number is jurisdiction-scoped automatically (via the Phase 2/3
 * global scope mechanism, not manual per-query filtering in this controller) and that
 * government roles genuinely cannot reach hotel financial data (the `reports` module),
 * only guest/occupancy data (`gov-reports`).
 */
class GovernmentReportsTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotelA; // Cox's Bazar
    private Hotel $hotelB; // Dhaka
    private User $dcCoxsbazar;
    private User $dcDhaka;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hotelA = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();

        $dhaka = District::where('name', 'Dhaka')->firstOrFail();
        $this->hotelB = Hotel::create([
            'name' => 'Dhaka Gov Test Hotel',
            'slug' => 'dhaka-gov-test-hotel',
            'mobile' => '01700000000',
            'address' => 'Dhaka',
            'district_id' => $dhaka->id,
            'police_station_id' => PoliceStation::where('district_id', $dhaka->id)->firstOrFail()->id,
            'status' => 'active',
        ]);

        $dcRole = Role::create(['name' => 'DC Office', 'slug' => 'dc-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'district']);
        foreach (['dashboard', 'gov-reports', 'customers'] as $m) {
            RolePermission::create(['role_id' => $dcRole->id, 'module_key' => $m, 'can_view' => true]);
        }

        $this->dcCoxsbazar = User::create(['name' => 'DC Coxsbazar', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $dcRole->id, 'district_id' => $this->hotelA->district_id, 'is_active' => true]);
        $this->dcDhaka     = User::create(['name' => 'DC Dhaka', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $dcRole->id, 'district_id' => $dhaka->id, 'is_active' => true]);

        $this->seedCheckedInGuest($this->hotelA, 'Guest A', ['nid_number' => 'NID-AAA-111']);
        $this->seedCheckedInGuest($this->hotelB, 'Guest B', ['nid_number' => 'NID-BBB-222', 'is_foreign_guest' => true, 'nationality' => 'American']);
    }

    private function seedCheckedInGuest(Hotel $hotel, string $name, array $customerOverrides = []): void
    {
        $roomType = RoomType::create(['hotel_id' => $hotel->id, 'name' => 'Standard', 'slug' => 'standard-' . uniqid()]);
        $room     = \App\Models\Room::create(['hotel_id' => $hotel->id, 'room_type_id' => $roomType->id, 'room_number' => 'R-' . uniqid()]);
        $customer = Customer::create(array_merge([
            'hotel_id' => $hotel->id,
            'name' => $name,
            'phone' => '017' . random_int(10000000, 99999999),
        ], $customerOverrides));

        $booking = RoomBooking::create([
            'hotel_id' => $hotel->id,
            'booking_reference' => 'TEST-' . uniqid(),
            'customer_id' => $customer->id,
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_amount' => 1000,
            'booking_status' => 'checked_in',
        ]);

        BookingRoom::create([
            'room_booking_id' => $booking->id,
            'room_type_id' => $roomType->id,
            'room_id' => $room->id,
            'adults' => 1,
            'children' => 0,
            'status' => 'checked_in',
            'actual_check_in_at' => now(),
        ]);
    }

    public function test_dc_sees_only_their_districts_hotel_in_hotel_report(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.government.hotels'))
            ->assertInertia(
                fn($page) => $page
                    ->has('rows', 1)
                    ->where('rows.0.hotel_name', $this->hotelA->name)
            );
    }

    public function test_dc_sees_only_their_districts_guests(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.government.guests'))
            ->assertInertia(
                fn($page) => $page
                    ->has('rows', 1)
                    ->where('rows.0.guest_name', 'Guest A')
            );

        $this->actingAs($this->dcDhaka)
            ->get(route('admin.government.guests'))
            ->assertInertia(
                fn($page) => $page
                    ->has('rows', 1)
                    ->where('rows.0.guest_name', 'Guest B')
            );
    }

    /**
     * Phase 7 hardening: a DC user maliciously (or accidentally, via a bookmarked/
     * shared URL) passing ?hotel_id=<a hotel outside their district> must get an
     * empty result, not the other district's guest. `guestRows()` ANDs the hotel_id
     * filter onto a query that's already scoped via whereHas('booking'), so this
     * should be safe by construction — this test exists to prove it, not assume it.
     */
    public function test_guest_report_hotel_filter_cannot_be_used_to_escape_jurisdiction(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.government.guests', ['hotel_id' => $this->hotelB->id]))
            ->assertInertia(fn($page) => $page->has('rows', 0));
    }

    /** Phase 7 hardening: a hotel-role account has no `gov-reports` permission at all. */
    public function test_hotel_staff_cannot_reach_any_government_report_route(): void
    {
        $staffRole = Role::create(['name' => 'Front Desk', 'slug' => 'fd-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        RolePermission::create(['role_id' => $staffRole->id, 'module_key' => 'customers', 'can_view' => true]);
        $staff = User::create(['name' => 'Staff', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $staffRole->id, 'hotel_id' => $this->hotelA->id, 'is_active' => true]);

        foreach (['admin.government.hotels', 'admin.government.guests', 'admin.government.nationality', 'admin.government.nid-search'] as $routeName) {
            $this->actingAs($staff)->get(route($routeName))->assertRedirect(route('admin.dashboard'));
        }
    }

    public function test_nid_search_is_scoped_to_jurisdiction(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.government.nid-search', ['query' => 'NID-BBB-222']))
            ->assertInertia(fn($page) => $page->has('results', 0));

        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.government.nid-search', ['query' => 'NID-AAA-111']))
            ->assertInertia(fn($page) => $page->has('results', 1));
    }

    public function test_government_role_cannot_reach_hotel_financial_reports(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.reports.income'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_foreign_only_filter_works(): void
    {
        $this->actingAs($this->dcDhaka)
            ->get(route('admin.government.guests', ['foreign_only' => 1]))
            ->assertInertia(fn($page) => $page->has('rows', 1)->where('rows.0.guest_name', 'Guest B'));
    }

    public function test_government_scoped_user_gets_the_government_dashboard(): void
    {
        $this->actingAs($this->dcCoxsbazar)
            ->get(route('admin.dashboard'))
            ->assertInertia(
                fn($page) => $page
                    ->component('Admin/Government/Dashboard')
                    ->where('stats.hotel_count', 1)
                    ->where('stats.current_guests', 1)
            );
    }

    public function test_super_admin_still_gets_the_hotel_operations_dashboard(): void
    {
        $admin = User::create(['name' => 'Super', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => null, 'is_active' => true]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertInertia(fn($page) => $page->component('Admin/Dashboard'));
    }

    public function test_nationality_report_counts_are_scoped(): void
    {
        $this->actingAs($this->dcDhaka)
            ->get(route('admin.government.nationality'))
            ->assertInertia(
                fn($page) => $page
                    ->has('rows', 1)
                    ->where('rows.0.nationality', 'American')
                    ->where('rows.0.count', 1)
            );
    }
}
