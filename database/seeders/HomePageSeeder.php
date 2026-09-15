<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $whyChooseUs = [
            'image' => 'assets/img/normal/wcu_2_1.png',
            'since_year' => '1998',
            'subtitle' => 'Why Choose Us',
            'title' => 'Leading Way In Real Estate Building & Civil Constructions!',
            'description' => 'Dramatically foster compelling result before vertical platforms. Globally pursue client focused potentiality without global alignment.',
            'features' => [
                [
                    'icon' => 'assets/img/icon/house-check.svg',
                    'title' => 'Commercial Service',
                    'text' => 'Proper lighting is crucial in interior design.',
                    'url' => 'service-details.html',
                ],
                [
                    'icon' => 'assets/img/icon/house-check.svg',
                    'title' => 'Residential Services',
                    'text' => 'This includes building homes, housing units.',
                    'url' => 'service-details.html',
                ],
                [
                    'icon' => 'assets/img/icon/house-check.svg',
                    'title' => 'Trusted Company',
                    'text' => 'Contractors are responsible for the entire',
                    'url' => 'service-details.html',
                ],
                [
                    'icon' => 'assets/img/icon/house-check.svg',
                    'title' => 'Dedicated Team',
                    'text' => 'Civil engineers are involved in everything',
                    'url' => 'service-details.html',
                ],
            ]
        ];

        GlobalSetting::set('home_page_why_choose_us', json_encode($whyChooseUs));

        $service = [
            'subtitle' => 'Our Services',
            'title' => 'The Best Service For You',
            'bg_image' => 'assets/img/update2/bg/service_bg_1.jpg'
        ];
        GlobalSetting::set('home_page_service', json_encode($service));

        $cta = [
            'title' => 'Have Any Question For Project Plan In Your Mind?',
            'button_text' => 'GET IN TOUCH',
            'button_url' => 'course.html',
            'bg_image' => 'assets/img/update1/bg/cta_bg_1.jpg'
        ];
        GlobalSetting::set('home_page_cta', json_encode($cta));

        $team = [
            'subtitle' => 'Team Members',
            'title' => 'Our Professional Team',
            'bg_image' => 'assets/img/update1/bg/team_bg_3.png'
        ];
        GlobalSetting::set('home_page_team', json_encode($team));

        $project = [
            'subtitle' => 'CONSTRUCT PROJECTS',
            'title' => 'Our Recent Projects',
            'bg_image' => 'assets/img/update1/bg/project_bg_3.jpg'
        ];
        GlobalSetting::set('home_page_project', json_encode($project));

        $achievements = [
            'subtitle' => 'Achivements',
            'title' => 'Let\'s Start We Are On Building Of Dream',
            'description' => 'Globally engineer ubiquitous ROI whereas visionary web-readiness. Objectively matrix optimal e-markets vis-a-vis empowered leadership skills. Professionally leadership skills aggregate fully tested.',
            'bg_image' => 'assets/img/update1/bg/achive_bg_1.jpg',
            'side_image' => 'assets/img/update1/normal/achive_1_1.jpg',
            'counters' => [
                ['number' => '25', 'suffix' => 'k+', 'text' => 'Complete Projects', 'icon' => 'assets/img/update1/icon/achive_1_1.svg'],
                ['number' => '16', 'suffix' => 'k+', 'text' => 'Active On Clients', 'icon' => 'assets/img/update1/icon/achive_1_2.svg'],
                ['number' => '1.2', 'suffix' => 'k+', 'text' => 'Experience Team', 'icon' => 'assets/img/update1/icon/achive_1_3.svg'],
                ['number' => '1.4', 'suffix' => 'k+', 'text' => 'Winning Awards', 'icon' => 'assets/img/update1/icon/achive_1_4.svg']
            ]
        ];
        GlobalSetting::set('home_page_achievements', json_encode($achievements));

        $testimonial = [
            'subtitle' => 'Testimonials',
            'title' => 'What Our Client Say?',
            'bg_image' => 'assets/img/update1/bg/testi_bg_4.jpg'
        ];
        GlobalSetting::set('home_page_testimonial', json_encode($testimonial));

        $blog = [
            'subtitle' => 'Latest Blog',
            'title' => 'Our Latest Construction News Blog & Articles'
        ];
        GlobalSetting::set('home_page_blog', json_encode($blog));

        $seo = [
            'meta_title' => 'Bondhan Living Ltd - Home',
            'meta_description' => 'Bondhan Living Ltd - Construction & Real Estate Company',
            'meta_keywords' => 'Bondhan Living Ltd, Construction, Real Estate',
            'meta_author' => 'Bondhan Living Ltd'
        ];
        GlobalSetting::set('home_page_seo', json_encode($seo));

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
