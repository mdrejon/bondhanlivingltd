<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Schema;

class GalleryPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $galleryData = [
            'gallery_page_hero' => [
                'title' => 'WORK GALLERY',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'gallery_page_images' => [
                ['image' => 'assets/img/gallery/gallery_1_1.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_2.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_3.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_4.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_5.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_6.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_7.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_8.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_9.jpg'],
                ['image' => 'assets/img/gallery/gallery_1_10.jpg'],
            ],
            'gallery_page_seo' => [
                'meta_title' => 'Work Gallery - Bondhan Living Ltd',
                'meta_description' => 'View the work gallery and completed projects of Bondhan Living Ltd.',
                'meta_keywords' => 'gallery, projects, photos, bondhan living',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($galleryData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        Schema::enableForeignKeyConstraints();
    }
}
