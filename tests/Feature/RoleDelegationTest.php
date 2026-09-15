<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Support\HotelDefaultRoleProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 8C — delegated role/user management. The core claims under test: a
 * hotel-scoped admin only ever sees/touches their own hotel's roles and users,
 * can never self-escalate (is_super_admin, an unrestricted null role_id, or
 * granting a module they don't themselves hold), and every newly created hotel
 * gets exactly the three default roles named in the original request.
 */
class RoleDelegationTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotelA;
    private Hotel $hotelB;
    private User $adminA;
    private Role $roleA;

    protected function setUp(): void
    {
        parent::setUp();

        $district = District::first();
        $policeStation = PoliceStation::where('district_id', $district->id)->firstOrFail();

        $this->hotelA = Hotel::withoutGlobalScopes()->create([
            'name' => 'Role Test Hotel A', 'slug' => 'role-test-hotel-a',
            'mobile' => '01700000020', 'address' => 'A', 'district_id' => $district->id, 'police_station_id' => $policeStation->id, 'status' => 'active',
        ]);
        $this->hotelB = Hotel::withoutGlobalScopes()->create([
            'name' => 'Role Test Hotel B', 'slug' => 'role-test-hotel-b',
            'mobile' => '01700000021', 'address' => 'B', 'district_id' => $district->id, 'police_station_id' => $policeStation->id, 'status' => 'active',
        ]);

        // A hotel-A role that can manage users/roles for hotel A, plus a couple
        // of other modules it holds (so we can prove it can grant those but
        // nothing beyond them).
        $this->roleA = Role::create(['hotel_id' => $this->hotelA->id, 'name' => 'Hotel A Admin', 'slug' => 'hotel-a-admin-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        RolePermission::create(['role_id' => $this->roleA->id, 'module_key' => 'user-management', 'can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]);
        RolePermission::create(['role_id' => $this->roleA->id, 'module_key' => 'bookings', 'can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]);
        // Deliberately NOT granted: backups, hotel-registration.

        $this->adminA = User::create(['name' => 'Admin A', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $this->roleA->id, 'hotel_id' => $this->hotelA->id, 'is_active' => true]);
    }

    public function test_hotel_admin_only_sees_their_own_hotels_roles(): void
    {
        Role::create(['hotel_id' => $this->hotelB->id, 'name' => 'Hotel B Role', 'slug' => 'hotel-b-role-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        Role::create(['hotel_id' => null, 'name' => 'Shared Platform Role', 'slug' => 'shared-role-' . uniqid(), 'is_super_admin' => false, 'is_active' => true]); // pre-8C shared role

        $this->actingAs($this->adminA)
            ->get(route('admin.roles.index'))
            ->assertInertia(fn ($page) => $page
                ->has('roles', 1)
                ->where('roles.0.id', $this->roleA->id)
            );
    }

    public function test_hotel_admin_cannot_set_is_super_admin(): void
    {
        $this->actingAs($this->adminA)->post(route('admin.roles.store'), [
            'name' => 'Sneaky Role',
            'is_super_admin' => true,
            'is_active' => true,
            'permissions' => [],
        ])->assertRedirect();

        $role = Role::where('name', 'Sneaky Role')->first();
        $this->assertNotNull($role);
        $this->assertFalse($role->is_super_admin);
        $this->assertSame($this->hotelA->id, $role->hotel_id);
    }

    public function test_hotel_admin_cannot_grant_a_module_they_do_not_hold(): void
    {
        $this->actingAs($this->adminA)->post(route('admin.roles.store'), [
            'name' => 'Overreaching Role',
            'is_active' => true,
            'permissions' => [
                ['key' => 'bookings', 'can_view' => true, 'can_create' => true, 'can_edit' => false, 'can_delete' => false],
                ['key' => 'backups', 'can_view' => true, 'can_create' => true, 'can_edit' => false, 'can_delete' => true],
            ],
        ])->assertRedirect();

        $role = Role::where('name', 'Overreaching Role')->first();
        $this->assertTrue($role->hasPermission('bookings', 'create'));
        $this->assertFalse($role->hasPermission('backups', 'view'));
        $this->assertFalse($role->hasPermission('backups', 'create'));
    }

    public function test_hotel_admin_cannot_edit_another_hotels_role(): void
    {
        $roleB = Role::create(['hotel_id' => $this->hotelB->id, 'name' => 'Hotel B Role', 'slug' => 'hotel-b-role-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);

        $this->actingAs($this->adminA)
            ->get(route('admin.roles.edit', $roleB->id))
            ->assertNotFound();

        $this->actingAs($this->adminA)
            ->put(route('admin.roles.update', $roleB->id), ['name' => 'Hijacked', 'is_active' => true])
            ->assertNotFound();
    }

    public function test_hotel_admin_cannot_edit_another_hotels_user(): void
    {
        $userB = User::create(['name' => 'User B', 'email' => uniqid() . '@t.local', 'password' => 'password', 'hotel_id' => $this->hotelB->id, 'is_active' => true]);

        $this->actingAs($this->adminA)
            ->get(route('admin.users.edit', $userB->id))
            ->assertNotFound();

        $this->actingAs($this->adminA)
            ->patch(route('admin.users.toggle', $userB->id))
            ->assertNotFound();
    }

    public function test_hotel_admin_cannot_create_a_user_with_no_role(): void
    {
        // role_id = null means Super Admin per User::isSuperAdmin() — must be rejected.
        $this->actingAs($this->adminA)->post(route('admin.users.store'), [
            'name' => 'Escalation Attempt', 'email' => uniqid() . '@t.local',
            'password' => 'password12', 'password_confirmation' => 'password12',
            'role_id' => null,
        ])->assertForbidden();

        $this->assertNull(User::where('email', 'like', '%escalation%')->first());
    }

    public function test_hotel_admin_cannot_assign_another_hotels_role_to_a_new_user(): void
    {
        $roleB = Role::create(['hotel_id' => $this->hotelB->id, 'name' => 'Hotel B Role', 'slug' => 'hotel-b-role-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);

        $this->actingAs($this->adminA)->post(route('admin.users.store'), [
            'name' => 'Cross Hotel User', 'email' => uniqid() . '@t.local',
            'password' => 'password12', 'password_confirmation' => 'password12',
            'role_id' => $roleB->id,
        ])->assertForbidden();
    }

    public function test_hotel_admin_created_user_is_forced_into_their_own_hotel(): void
    {
        $this->actingAs($this->adminA)->post(route('admin.users.store'), [
            'name' => 'New Staff', 'email' => uniqid() . '@t.local',
            'password' => 'password12', 'password_confirmation' => 'password12',
            'role_id' => $this->roleA->id,
            'hotel_id' => $this->hotelB->id, // attempted escalation — should be ignored
        ])->assertRedirect();

        $user = User::where('name', 'New Staff')->first();
        $this->assertSame($this->hotelA->id, $user->hotel_id);
    }

    public function test_new_hotel_gets_exactly_three_default_roles(): void
    {
        HotelDefaultRoleProvisioner::provision($this->hotelB);

        $roles = Role::where('hotel_id', $this->hotelB->id)->pluck('name')->sort()->values()->all();
        $this->assertSame(['Hotel Admin', 'Reception', 'Staff'], $roles);

        $hotelAdmin = Role::where('hotel_id', $this->hotelB->id)->where('name', 'Hotel Admin')->first();
        $this->assertTrue($hotelAdmin->hasPermission('website-management', 'delete'));
        $this->assertTrue($hotelAdmin->hasPermission('hotel-backups', 'create'));
        $this->assertFalse($hotelAdmin->hasPermission('hotel-registration', 'view'));
        $this->assertFalse($hotelAdmin->hasPermission('gov-reports', 'view'));
        $this->assertFalse($hotelAdmin->hasPermission('backups', 'view'));

        $staff = Role::where('hotel_id', $this->hotelB->id)->where('name', 'Staff')->first();
        $this->assertTrue($staff->hasPermission('bookings', 'view'));
        $this->assertFalse($staff->hasPermission('bookings', 'create'));
    }

    public function test_super_admin_is_unaffected_by_hotel_scoping(): void
    {
        $superAdmin = User::create(['name' => 'Super', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => null, 'is_active' => true]);
        Role::create(['hotel_id' => $this->hotelB->id, 'name' => 'Hotel B Role', 'slug' => 'hotel-b-role-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);

        $this->actingAs($superAdmin)
            ->get(route('admin.roles.index'))
            ->assertInertia(fn ($page) => $page->has('roles', 2)); // roleA + Hotel B Role
    }
}
