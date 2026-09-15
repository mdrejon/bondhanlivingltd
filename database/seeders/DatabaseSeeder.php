<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoomAmenitySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BdGeographySeeder::class);
        $this->call(GovernmentRoleSeeder::class);
        $this->call(SliderSeeder::class);
        $this->call(HomePageSeeder::class);
    }
}
