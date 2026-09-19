<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        Slider::truncate();

        Slider::create([
            'title'            => 'we care & we share ...',
            'description'      => 'Bondhan Living Ltd. have reputation as a reliable real estate company that gives high priority to customer desire and needs, <br /> has been created through the hard work and dedication of its work force.',
            'button_text'      => 'Read More',
            'button_url'       => '#.',
            'background_image' => 'images/banner-4.jpg',
            'sort_order'       => 1,
            'is_active'        => true,
        ]);

        Slider::create([
            'title'            => 'we care & we share ...',
            'description'      => 'Bondhan Living Ltd. have reputation as a reliable real estate company that gives high priority to customer desire and needs, <br /> has been created through the hard work and dedication of its work force.',
            'button_text'      => 'Read More',
            'button_url'       => '#.',
            'background_image' => 'images/banner-5.jpg',
            'sort_order'       => 2,
            'is_active'        => true,
        ]);

        Slider::create([
            'title'            => 'we care & we share ...',
            'description'      => 'Bondhan Living Ltd. have reputation as a reliable real estate company that gives high priority to customer desire and needs, <br /> has been created through the hard work and dedication of its work force.',
            'button_text'      => 'Read More',
            'button_url'       => '#.',
            'background_image' => 'images/banner-6.jpg',
            'sort_order'       => 3,
            'is_active'        => true,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
