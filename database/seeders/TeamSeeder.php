<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Mishel Marsh',
                'designation' => 'Founder',
                'image' => 'assets/img/team/team_1_1.jpg',
                'facebook' => 'https://facebook.com/',
                'twitter' => 'https://twitter.com/',
                'linkedin' => 'https://linkedin.com/',
                'status' => true,
                'order' => 1,
            ],
            [
                'name' => 'Michel Richard',
                'designation' => 'Architecture',
                'image' => 'assets/img/team/team_1_2.jpg',
                'facebook' => 'https://facebook.com/',
                'twitter' => 'https://twitter.com/',
                'linkedin' => 'https://linkedin.com/',
                'status' => true,
                'order' => 2,
            ],
            [
                'name' => 'Famhida Ruko',
                'designation' => 'Engineer',
                'image' => 'assets/img/team/team_1_3.jpg',
                'facebook' => 'https://facebook.com/',
                'twitter' => 'https://twitter.com/',
                'linkedin' => 'https://linkedin.com/',
                'status' => true,
                'order' => 3,
            ],
            [
                'name' => 'Alex Anfantino',
                'designation' => 'Site Manager',
                'image' => 'assets/img/team/team_1_4.jpg',
                'facebook' => 'https://facebook.com/',
                'twitter' => 'https://twitter.com/',
                'linkedin' => 'https://linkedin.com/',
                'status' => true,
                'order' => 4,
            ],
        ];

        foreach ($teams as $team) {
            \App\Models\Team::create($team);
        }
    }
}
