<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Contemporary Villa',
                'category' => 'Constructions',
                'status' => 'running',
                'client' => 'Benoit Architecture',
                'location' => '28B Highgate Road, London',
                'description' => "Nullam metus metus, imperdiet ut ex quis, ultrices feugiat neque. Etiam vitae accumsan neque, id gravida ligula. Donec ut tincidunt velit. Sed gravida erat nunc, ac vehicula orci dignissim id. Praesent diam magna. Sed tincidunt mi libero.\n\nPhasellus sed pellentesque neque, sit amet porta quam. Donec sapien odio, eleifend mattis tristique ut, finibus ac augue. Suspendisse potenti. Etiam porttitor mi lorem, ut mattis mauris rutrum in. Mauris nibh sapien, ornare at dui ac, placerat pulvinar nisi.",
                'thumbnail' => 'assets/img/project/project-3-3.png',
            ],
            [
                'title' => 'Interior Texture',
                'category' => 'Interior',
                'status' => 'completed',
                'client' => 'Mary Cruzleen',
                'location' => 'New York, USA',
                'description' => "Efficiently administrate effective outsourcing before process-centric deliverables. Phosfluorescently grow exceptional quality vectors and excellent core competency.",
                'thumbnail' => 'assets/img/project/project-3-1.png',
            ],
            [
                'title' => 'Benoit Architecture',
                'category' => 'Architecture',
                'status' => 'upcoming',
                'client' => 'David Milton',
                'location' => 'Sydney, Australia',
                'description' => "Objectively mesh client-centric interfaces with tactical platforms. Progressively benchmark frictionless.",
                'thumbnail' => 'assets/img/project/project-3-2.png',
            ],
            [
                'title' => 'House of Cards',
                'category' => 'Constructions',
                'status' => 'running',
                'client' => 'Abraham Khalil',
                'location' => 'Dhaka, Bangladesh',
                'description' => "Globally optimize highly efficient solution whereas open-source application. Completely strategize quality internal or organic sources for virtual e-business.",
                'thumbnail' => 'assets/img/project/project-3-4.png',
            ],
            [
                'title' => 'Kitchen and Living',
                'category' => 'Interior',
                'status' => 'completed',
                'client' => 'Rowson Construction',
                'location' => 'Toronto, Canada',
                'description' => "Seamlessly restore inexpensive e-markets. Authoritatively scale business meta-services before client-based technologies.",
                'thumbnail' => 'assets/img/project/project-3-5.png',
            ],
            [
                'title' => 'Bridge Trangle Core',
                'category' => 'Architecture',
                'status' => 'running',
                'client' => 'Government',
                'location' => 'California, USA',
                'description' => "Collaboratively strategize synergistic scenarios rather than flexible action items. Continually deliver market positioning convergence.",
                'thumbnail' => 'assets/img/update1/project/project_4_2.jpg',
            ],
            [
                'title' => 'Rowson Construction',
                'category' => 'Constructions',
                'status' => 'completed',
                'client' => 'Private Investor',
                'location' => 'Texas, USA',
                'description' => "An architecture company thrives on innovation and creativity. Designers explore new materials, technologies, and design trends to deliver fresh and unique solutions.",
                'thumbnail' => 'assets/img/update1/project/project_4_3.jpg',
            ],
            [
                'title' => 'Interior Decoration',
                'category' => 'Interior',
                'status' => 'upcoming',
                'client' => 'Retail Chain',
                'location' => 'Paris, France',
                'description' => "We use the latest diagnostic equipment. Automotive service our clients receive.",
                'thumbnail' => 'assets/img/update1/project/project_4_4.jpg',
            ],
            [
                'title' => 'Construction Planning',
                'category' => 'Planning',
                'status' => 'running',
                'client' => 'Urban Developers',
                'location' => 'Berlin, Germany',
                'description' => "Digital how will activities impact traditional architect and technical engineer.",
                'thumbnail' => 'assets/img/update1/project/project_4_5.jpg',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => Str::slug($project['title'])],
                [
                    'title' => $project['title'],
                    'category' => $project['category'],
                    'status' => $project['status'],
                    'client' => $project['client'],
                    'location' => $project['location'],
                    'description' => $project['description'],
                    'thumbnail' => $project['thumbnail'],
                ]
            );
        }
    }
}
