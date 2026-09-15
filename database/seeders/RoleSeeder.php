<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name'           => 'Super Admin',
                'description'    => 'Full access to all modules.',
                'is_super_admin' => true,
                'is_active'      => true,
            ]
        );

        // Assign all existing users without a role to Super Admin
        \App\Models\User::whereNull('role_id')->update(['role_id' => $superAdmin->id]);

        $this->command->info("Super Admin role created and assigned to all existing users.");
    }
}
