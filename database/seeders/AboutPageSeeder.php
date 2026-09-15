<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $hero = [
            'title' => 'About Us',
            'bg_image' => 'assets/img/bg/breadcumb-bg.jpg'
        ];
        GlobalSetting::set('about_page_hero', json_encode($hero));

        $company = [
            'years_experience' => '25',
            'years_text' => 'Years Experiences Of Construction Company',
            'subtitle' => 'About Us Company',
            'title' => 'We Are Always Think On Your Dream',
            'description' => 'Many modern construction companies focus on sustainable building practices, incorporating eco-friendly material energy-efficient systems and environmental conscious designs to reduce the environmental impact of their projects.',
            'image1' => 'assets/img/normal/about_1_1.png',
            'image2' => 'assets/img/normal/about_1_2.png',
            'shape_image' => 'assets/img/normal/about_1_shape1.png',
            'features' => [
                [
                    'icon' => 'assets/img/icon/about-grid-icon1.svg',
                    'title' => 'Worldwide Services',
                    'text' => 'They provide clients with transparent cost estimates and adhere to the agreed-upon'
                ],
                [
                    'icon' => 'assets/img/icon/about-grid-icon2.svg',
                    'title' => 'Best Company Award Winner',
                    'text' => 'A reliable construction company is adept at managing budgets and timelines effectively.'
                ]
            ]
        ];
        GlobalSetting::set('about_page_company', json_encode($company));

        $achievements = [
            'subtitle' => 'Our Company Achievements',
            'title' => 'Industrial Strength, Global Impact',
            'button_text' => 'Make An Appointment',
            'button_url' => 'contact.html',
            'counters' => [
                ['number' => '4', 'suffix' => 'k+', 'text' => 'Projects Complete', 'icon' => 'assets/img/icon/counter-icon4-1.svg'],
                ['number' => '3.5', 'suffix' => 'k+', 'text' => 'Our Team Members', 'icon' => 'assets/img/icon/counter-icon4-2.svg'],
                ['number' => '2.5', 'suffix' => 'k+', 'text' => 'Clients Are Happy', 'icon' => 'assets/img/icon/counter-icon4-3.svg'],
                ['number' => '1', 'suffix' => 'k+', 'text' => 'Winning Awards', 'icon' => 'assets/img/icon/counter-icon4-4.svg']
            ]
        ];
        GlobalSetting::set('about_page_achievements', json_encode($achievements));

        $mission = [
            'subtitle' => 'Why Choose Us Our Company',
            'title' => 'We Help You Build On Your Past And Prepare For The Feature',
            'bg_shape' => 'assets/img/service/service-bg-shape2-1.png',
            'tabs' => [
                [
                    'tab_name' => 'Our Mission',
                    'image' => 'assets/img/normal/mission_1_2.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'Leading Way In Building & Civil Constructions!',
                    'text' => 'Dramatically foster compelling result before vertical platforms. Globally pursue client focused potentiality without global alignment. Dramatical maximize covalent data with world-class schemas.',
                    'checklist' => [
                        ['text' => 'Commercial Services'],
                        ['text' => 'Residential Services'],
                        ['text' => 'Industrial Services'],
                        ['text' => 'Construction Service']
                    ],
                    'features' => [
                        ['icon' => 'assets/img/icon/mission_1_1.svg', 'subtitle' => 'Planning', 'title' => 'Construction Services'],
                        ['icon' => 'assets/img/icon/mission_1_2.svg', 'subtitle' => 'Planning', 'title' => 'Architecture Design']
                    ]
                ],
                [
                    'tab_name' => 'Our Vision',
                    'image' => 'assets/img/normal/mission_1_3.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'We Always Provide Best Quality Building Service.',
                    'text' => 'Dramatically foster compelling result before vertical platforms. Globally pursue client focused potentiality without global alignment. Dramatical maximize covalent data with world-class schemas.',
                    'checklist' => [
                        ['text' => 'Commercial Services'],
                        ['text' => 'Residential Services'],
                        ['text' => 'Industrial Services'],
                        ['text' => 'Construction Service']
                    ],
                    'features' => [
                        ['icon' => 'assets/img/icon/mission_1_1.svg', 'subtitle' => 'Planning', 'title' => 'Construction Services'],
                        ['icon' => 'assets/img/icon/mission_1_2.svg', 'subtitle' => 'Planning', 'title' => 'Architecture Design']
                    ]
                ],
                [
                    'tab_name' => 'Company Goal',
                    'image' => 'assets/img/normal/mission_1_4.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'We Got A Lots Of Award During The Century.',
                    'text' => 'Dramatically foster compelling result before vertical platforms. Globally pursue client focused potentiality without global alignment. Dramatical maximize covalent data with world-class schemas.',
                    'checklist' => [
                        ['text' => 'Commercial Services'],
                        ['text' => 'Residential Services'],
                        ['text' => 'Industrial Services'],
                        ['text' => 'Construction Service']
                    ],
                    'features' => [
                        ['icon' => 'assets/img/icon/mission_1_1.svg', 'subtitle' => 'Planning', 'title' => 'Construction Services'],
                        ['icon' => 'assets/img/icon/mission_1_2.svg', 'subtitle' => 'Planning', 'title' => 'Architecture Design']
                    ]
                ]
            ]
        ];
        GlobalSetting::set('about_page_mission', json_encode($mission));

        $process = [
            'subtitle' => 'How It Works',
            'title' => 'Our Work Process',
            'cards' => [
                ['icon' => 'assets/img/icon/process-icon-1-1.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 01', 'title' => 'Project Research', 'text' => 'Industrial manufacturing products have a global impact, supporting various sectors and markets worldwide.'],
                ['icon' => 'assets/img/icon/process-icon-1-2.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 02', 'title' => 'Quality Products', 'text' => 'Industrial manufacturing products have a global impact, supporting various sectors and markets worldwide.'],
                ['icon' => 'assets/img/icon/process-icon-1-3.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 03', 'title' => 'Start Working', 'text' => 'Industrial manufacturing products have a global impact, supporting various sectors and markets worldwide.'],
                ['icon' => 'assets/img/icon/process-icon-1-4.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 04', 'title' => 'Finished Work', 'text' => 'Industrial manufacturing products have a global impact, supporting various sectors and markets worldwide.']
            ]
        ];
        GlobalSetting::set('about_page_process', json_encode($process));

        $seo = [
            'meta_title' => 'Bondhan Living Ltd - About Us',
            'meta_description' => 'Bondhan Living Ltd - Construction & Real Estate Company',
            'meta_keywords' => 'Bondhan Living Ltd, Construction, Real Estate',
            'meta_author' => 'Bondhan Living Ltd'
        ];
        GlobalSetting::set('about_page_seo', json_encode($seo));

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
