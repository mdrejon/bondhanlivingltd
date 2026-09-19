<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Str;

class RunningProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Bondhan Swapno Bilash',
                'location' => 'Mohammadpur R/A Panchlaish, Chittagong',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/swapno-bilash.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan S R Cottage',
                'location' => 'Al- Falah Housing Society,East Nasirabad, Chittagong.',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/sr-cottage.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Kazi Palace',
                'location' => 'Shahid Shoidullah Kaiser Road, Feni.',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/kazi-palace.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Ahamed Tower',
                'location' => 'Shahid Shoidullah Kaiser Road, Feni.',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/ahamed-tower.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Heaven',
                'location' => 'Masterpara Road, Feni',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/heaven.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Ataur Center',
                'location' => 'Pathan Bari Road, Feni.',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/ataur-center.jpg',
                'category' => 'Constructions'
            ],
            [
                'title' => 'Bondhan Dilruba',
                'location' => 'Mizan Road, Feni.',
                'status' => 'running',
                'thumbnail' => 'assets/img/project/dilruba.jpg',
                'category' => 'Constructions'
            ],
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
                    'description' => 'A running project by Bondhan Living Ltd.',
                    'thumbnail' => $project['thumbnail'],
                ]
            );
        }
    }
}
