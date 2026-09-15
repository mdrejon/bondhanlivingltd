<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalSetting;

class CompanyPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // 1. History Page Default Data
        $historyData = [
            'history_page_hero' => [
                'title' => 'History',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'history_page_main' => [
                'subtitle' => 'Journey of Bondhan Living',
                'title' => 'Our Glorious History',
                'description' => 'From a humble beginning to becoming one of the most trusted real estate developers in the country, explore the milestones that defined our success.',
            ],
            'history_page_timeline' => [
                [
                    'year' => '2010',
                    'title' => 'The Beginning',
                    'description' => 'Bondhan Living Limited was established with a vision to redefine the real estate landscape. We began our journey in Chittagong, aiming to provide accommodations for various income groups while focusing on quality and affordability.',
                    'image' => 'assets/img/project/project_1_1.jpg',
                ],
                [
                    'year' => '2014',
                    'title' => 'First Major Milestone',
                    'description' => 'Successfully completed and handed over our first major residential complex in Chittagong. This project set a benchmark for quality and timely delivery, earning us the trust of our initial customer base.',
                    'image' => 'assets/img/project/project_1_2.jpg',
                ],
                [
                    'year' => '2018',
                    'title' => 'Expansion & Innovation',
                    'description' => 'Integrated advanced structural methodologies and implemented our Management Information System (MIS). We expanded our operations to Feni and started focusing on luxurious, self-contained apartment complexes.',
                    'image' => 'assets/img/project/project_1_3.jpg',
                ],
                [
                    'year' => '2022',
                    'title' => 'Award of Excellence',
                    'description' => "Recognized as one of the steadily growing real estate developers. We won the 'Best Residential Architecture' award for combining modern aesthetics with functional qualities in our signature 10-apartment project.",
                    'image' => 'assets/img/project/project_1_4.jpg',
                ],
                [
                    'year' => '2026 - Present',
                    'title' => 'A Trusted Legacy',
                    'description' => 'Today, Bondhan Living is synonymous with trust and family-type customer relationships. We continue our unrelenting quest for excellence, building modern communities and shaping the urban outlook of the future.',
                    'image' => 'assets/img/project/project_1_5.jpg',
                ],
            ],
            'history_page_seo' => [
                'meta_title' => 'History - Bondhan Living Ltd',
                'meta_description' => 'Explore the journey and milestones of Bondhan Living Ltd.',
                'meta_keywords' => 'history, journey, milestones, bondhan living',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($historyData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        // 2. Chairman Message Default Data
        $chairmanData = [
            'chairman_page_hero' => [
                'title' => 'CHAIRMAN MESSAGE',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'chairman_page_main' => [
                'subtitle' => 'Message from the Chairman',
                'title' => 'CHAIRMAN',
                'highlight' => 'MESSAGE',
                'bg_image' => 'assets/img/update1/bg/achive_bg_1.jpg',
                'description' => 'Bondhan Living Ltd. is one of the steadily growing real estate developers in the country. specializes in developing most modern and luxurious apartments combining practically with best aesthetical and functional qualities. That uniquely poised as one of the luxurious apartment complexes consisting of self-contained apartments, reserved car parking, International standard lifts, generators and other general features as described in details. This time we are building 10 (ten) exclusive apartments in Chittagong City & Feni. We, the family are constantly working towards upgrading and improving every aspect of our activity to ensure our service for making prospective situation for our clients. Be it the quality of our architectural designs or our after-sales service, the emphasis is to keep on not only improving but also creating a family type relationship. It is because of this unrelenting quest for excellence that we have earned the goodwill of so many of our existing customers and ensuring benefits of having new relations with new members still now.',
                'image' => 'assets/img/team/team_1_1.jpg',
            ],
            'chairman_page_info' => [
                'salutation' => 'Thanking you.',
                'name' => 'Md. Ferdous Hasan',
                'designation' => 'Managing Director',
                'company' => 'Bondhan Living Ltd.',
            ],
            'chairman_page_seo' => [
                'meta_title' => 'Chairman Message - Bondhan Living Ltd',
                'meta_description' => 'Message from the Chairman of Bondhan Living Ltd.',
                'meta_keywords' => 'chairman message, message, md, bondhan living',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($chairmanData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        // 3. Corporate Background Default Data
        $corporateData = [
            'corporate_page_hero' => [
                'title' => 'Corporate Background',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'corporate_page_main' => [
                'subtitle' => 'Corporate Background',
                'title' => 'CORPORATE',
                'highlight' => 'BACKGROUND',
                'bg_image' => 'assets/img/update1/bg/achive_bg_1.jpg',
                'description' => 'Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally.Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally.Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally.or desires to obtain Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain desires to obtain pain of itself.',
                'image' => 'assets/img/update1/normal/achive_1_1.jpg',
            ],
            'corporate_page_seo' => [
                'meta_title' => 'Corporate Background - Bondhan Living Ltd',
                'meta_description' => 'Corporate Background of Bondhan Living Ltd.',
                'meta_keywords' => 'corporate, background, bondhan living',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($corporateData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
