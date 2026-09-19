<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Str;

class CompletedProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Bondhan Sultan Palace',
                'location' => 'Joynagar 2 No Lane, Chawkbazar, Chittagong',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/sultan-palace.jpeg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Rose',
                'location' => 'Dampara Lane, Dampara M M Ali Road, Chittagong',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/rose.jpeg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Arshad Manor',
                'location' => 'Kapashgola, Panchlaish, Chittagong.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/arshad-manor.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Salma Heights',
                'location' => 'Masjid Goli, East Nasirabad,Chittagong.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/salma.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Nabi Heritage',
                'location' => 'Eidgha, Bou Bazar,Chittagong.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/nabi.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Shuchona',
                'location' => 'Al-Falah Housing Society,East Nasirabad, Chittagong.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/shuchona.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Rajprashad',
                'location' => 'Godown Quarter,Academy Road, Feni.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/rajprashad.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Chowdhury Tower',
                'location' => 'Masterpara Road, Feni.',
                'status' => 'completed',
                'thumbnail' => 'assets/img/project/chowdhury-tower.jpg',
                'category' => 'Constructions'
            ]
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => Str::slug($project['title'])],
                [
                    'title' => $project['title'],
                    'category' => $project['category'],
                    'status' => $project['status'],
                    'client' => 'Unknown',
                    'location' => $project['location'],
                    'description' => 'A completed project by Bondhan Living Ltd.',
                    'thumbnail' => $project['thumbnail'],
                ]
            );
        }
    }
}
