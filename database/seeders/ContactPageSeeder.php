<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Schema;

class ContactPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $contactData = [
            'contact_page_hero' => [
                'title' => 'Contact Us',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'contact_page_info' => [
                'title' => 'Our Contact Information',
                'office_label' => 'Corporate Office',
                'office_address' => 'House No. 18/B, (1st Floor) Mehedibag Road, Chittagong',
                'office_map_url' => 'https://maps.google.com/?q=House+No+18B+Mehedibag+Road+Chittagong',
                'phone_label' => 'Contact Number',
                'phone_1' => '+8801740 574 490',
                'phone_2' => '+8801911 187 557',
                'email' => 'info@bondhanlivingltd.com',
                'hours_label' => 'Hours of Operation',
                'hours_1' => 'Saturday - Thursday: 9:00am - 6:00pm',
                'hours_2' => 'Friday: Closed',
            ],
            'contact_page_map' => [
                'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sth!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd'
            ],
            'contact_page_seo' => [
                'meta_title' => 'Contact Us - Bondhan Living Ltd',
                'meta_description' => 'Get in touch with Bondhan Living Ltd for your real estate and construction needs.',
                'meta_keywords' => 'contact, address, phone, email, bondhan living',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($contactData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        Schema::enableForeignKeyConstraints();
    }
}
