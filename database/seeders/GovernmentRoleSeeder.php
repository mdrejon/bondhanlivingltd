<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the three government role types from the HGRM R&D (DC Office, UNO Office,
 * Police Admin) plus 2+ test accounts per role across different jurisdictions, so
 * Phase 3's isolation mechanics have real accounts to manually QA and automate tests
 * against. See docs/hgrm-saas/WORKFLOW-ROADMAP.md Phase 3.
 *
 * Test account passwords are all "password" — dev/QA only, never for production use.
 */
class GovernmentRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::where('slug', 'super-admin')->update(['scope_type' => 'platform']);

        $dcRole     = $this->makeRole('DC Office', 'dc-office', 'district');
        $unoRole    = $this->makeRole('UNO Office', 'uno-office', 'upazila');
        $policeRole = $this->makeRole('Police Admin', 'police-admin', 'police_station');

        $coxsbazar     = District::where('name', 'Coxsbazar')->firstOrFail();
        $dhaka         = District::where('name', 'Dhaka')->firstOrFail();
        $coxsbazarSadarUpazila = Upazila::where('district_id', $coxsbazar->id)->where('name', 'Coxsbazar Sadar')->firstOrFail();
        $coxsbazarSadarPs     = PoliceStation::where('upazila_id', $coxsbazarSadarUpazila->id)->firstOrFail();

        $this->makeUser('DC Office (Cox\'s Bazar)', 'dc.coxsbazar@hgrm.test', $dcRole, ['district_id' => $coxsbazar->id]);
        $this->makeUser('DC Office (Dhaka)', 'dc.dhaka@hgrm.test', $dcRole, ['district_id' => $dhaka->id]);
        $this->makeUser('UNO Office (Coxsbazar Sadar)', 'uno.coxsbazarsadar@hgrm.test', $unoRole, ['upazila_id' => $coxsbazarSadarUpazila->id]);
        $this->makeUser('Police Admin (Coxsbazar Sadar)', 'police.coxsbazarsadar@hgrm.test', $policeRole, ['police_station_id' => $coxsbazarSadarPs->id]);

        $this->command->info('Government roles + test accounts seeded (DC Office, UNO Office, Police Admin). Test password: "password".');
    }

    private function makeRole(string $name, string $slug, string $scopeType): Role
    {
        $role = Role::updateOrCreate(
            ['slug' => $slug],
            [
                'name'           => $name,
                'description'    => "Read-only government monitoring access, scoped by {$scopeType}.",
                'is_super_admin' => false,
                'is_active'      => true,
                'scope_type'     => $scopeType,
            ]
        );

        foreach (['dashboard', 'gov-reports', 'customers'] as $module) {
            RolePermission::updateOrCreate(
                ['role_id' => $role->id, 'module_key' => $module],
                ['can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false]
            );
        }

        return $role;
    }

    private function makeUser(string $name, string $email, Role $role, array $jurisdiction): void
    {
        User::updateOrCreate(
            ['email' => $email],
            array_merge([
                'name'      => $name,
                'password'  => Hash::make('password'),
                'role_id'   => $role->id,
                'hotel_id'  => null,
                'is_active' => true,
            ], $jurisdiction)
        );
    }
}
