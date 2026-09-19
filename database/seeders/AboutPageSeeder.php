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
            'years_text' => 'Years Experiences Of Real Estate Company',
            'subtitle' => 'About Us Company',
            'title' => 'Welcome to Bondhan Living Limited',
            'description' => 'Bondhan Living Ltd. have reputation as a reliable real estate company that gives high priority to customer desire and needs, has been created through the hard work and dedication of its work force. Instead of just stressing on good academic qualification which is compulsory for the executive level employees, Bondhan Living has selected its staff based on their intelligence, creativity and innovation.',
            'image1' => 'assets/img/normal/about_1_1.png',
            'image2' => 'assets/img/normal/about_1_2.png',
            'shape_image' => 'assets/img/normal/about_1_shape1.png',
            'features' => [
                [
                    'icon' => 'assets/img/icon/about-grid-icon1.svg',
                    'title' => '24/7 Emergency Available',
                    'text' => 'We are always available to assist you with any emergency.'
                ],
                [
                    'icon' => 'assets/img/icon/about-grid-icon2.svg',
                    'title' => 'Expert and Professional',
                    'text' => 'Our highly skilled administration team coordinates all departments.'
                ]
            ]
        ];
        GlobalSetting::set('about_page_company', json_encode($company));

        $achievements = [
            'subtitle' => 'Our Company Achievements',
            'title' => 'Satisfaction Guarantee, Free Inspection',
            'button_text' => 'Make An Appointment',
            'button_url' => 'contact',
            'counters' => [
                ['number' => '4', 'suffix' => 'k+', 'text' => 'Projects Complete', 'icon' => 'assets/img/icon/counter-icon4-1.svg'],
                ['number' => '3.5', 'suffix' => 'k+', 'text' => 'Our Team Members', 'icon' => 'assets/img/icon/counter-icon4-2.svg'],
                ['number' => '2.5', 'suffix' => 'k+', 'text' => 'Clients Are Happy', 'icon' => 'assets/img/icon/counter-icon4-3.svg'],
                ['number' => '1', 'suffix' => 'k+', 'text' => 'Winning Awards', 'icon' => 'assets/img/icon/counter-icon4-4.svg']
            ]
        ];
        GlobalSetting::set('about_page_achievements', json_encode($achievements));

        $mission = [
            'subtitle' => 'Why Choose Us',
            'title' => 'We Help You Build On Your Past And Prepare For The Future',
            'bg_shape' => 'assets/img/service/service-bg-shape2-1.png',
            'tabs' => [
                [
                    'tab_name' => 'Our Mission',
                    'image' => 'assets/img/normal/mission_1_2.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'MISSION',
                    'text' => '',
                    'checklist' => [
                        ['text' => 'To maintain a high standard of quality of finish.'],
                        ['text' => 'To maintain a safe environment.'],
                        ['text' => 'Design based on Bangladesh National Building Code to withstand wind and earthquake load criteria.'],
                        ['text' => 'To maintain value for money.'],
                        ['text' => 'Maintain outstanding service to its clients.'],
                        ['text' => 'Continue good communications procedures.'],
                        ['text' => 'Continue to promote a sense of corporate identity within all the staff team.'],
                        ['text' => 'Continue to provide staff training to provide Excellent Customer Care.'],
                        ['text' => 'Continue develop a \'brand name\'.'],
                        ['text' => 'Marketing and promoting to individuals for quality homes in Bangladesh.'],
                        ['text' => 'Marketing and promoting to own luxury homes for investment or comfort living.']
                    ],
                    'features' => []
                ],
                [
                    'tab_name' => 'Our Vision',
                    'image' => 'assets/img/normal/mission_1_3.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'VISSION',
                    'text' => '',
                    'checklist' => [
                        ['text' => 'To maintain the highest standards in developing commercial properties.'],
                        ['text' => 'To maintain the highest standards in developing homes for individuals.'],
                        ['text' => 'To create safe homes.'],
                        ['text' => 'To provide feeling of living in a home with ultimate comfort.'],
                        ['text' => 'To provide good customer service.'],
                        ['text' => 'To provide professional and personalized services of the highest integrity.'],
                        ['text' => 'To become an International referral for buyers and investors.'],
                        ['text' => 'To provide high quality Customer Relationship Management.'],
                        ['text' => 'To develop a \'brand name\'.']
                    ],
                    'features' => []
                ],
                [
                    'tab_name' => 'Company Goal',
                    'image' => 'assets/img/normal/mission_1_4.jpg',
                    'video_url' => 'https://www.youtube.com/watch?v=_sI_Ps7JSEk',
                    'title' => 'Company Goal',
                    'text' => 'Our ultimate goal is to provide exceptional service and secure safe, long-term investments for our clients through our robust real estate solutions.',
                    'checklist' => [
                        ['text' => 'Client Satisfaction'],
                        ['text' => 'Quality Construction'],
                        ['text' => 'Safety First'],
                        ['text' => 'Innovation and Creativity']
                    ],
                    'features' => []
                ]
            ]
        ];
        GlobalSetting::set('about_page_mission', json_encode($mission));

        $process = [
            'subtitle' => 'How It Works',
            'title' => 'Our Work Process',
            'cards' => [
                ['icon' => 'assets/img/icon/process-icon-1-1.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 01', 'title' => 'Professional Team', 'text' => 'Bondhan Living has a professional & highly skilled Administration team to co-ordinate all departments of the company.'],
                ['icon' => 'assets/img/icon/process-icon-1-2.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 02', 'title' => 'High Security', 'text' => 'Adequate number of security guards working round the clock to ensure the security of the residents.'],
                ['icon' => 'assets/img/icon/process-icon-1-3.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 03', 'title' => 'Customer Desire', 'text' => 'Gives high priority to customer desire and needs, has been created through the hard work and dedication of its work force.'],
                ['icon' => 'assets/img/icon/process-icon-1-4.svg', 'bg_shape' => 'assets/img/bg/process_card_bg_1.png', 'subtitle' => 'Step - 04', 'title' => 'Satisfaction Guarantee', 'text' => 'We are providing 24/7 emergency availability and expert services to ensure your complete satisfaction.']
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
