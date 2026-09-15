<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        Slider::updateOrCreate(['id' => 1], [
            'hotel_id'         => 0,
            'label'            => 'BONDHON',
            'title'            => 'We are providing best <span>construction</span> service',
            'description'      => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio architecto culpa, eveniet inventore veritatis minus. Corporis molestias velit ab asperiores amet doloremque expedita in eos quasi',
            'button_text'      => 'View more',
            'button_url'       => 'about.html',
            'sort_order'       => 1,
            'is_active'        => true,
        ]);

        Slider::updateOrCreate(['id' => 2], [
            'hotel_id'         => 0,
            'label'            => 'BONDHON',
            'title'            => '<span>Construction</span> & infrastructure services company',
            'description'      => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio architecto culpa, eveniet inventore veritatis minus. Corporis molestias velit ab asperiores amet doloremque expedita in eos quasi',
            'button_text'      => 'View more',
            'button_url'       => 'about.html',
            'sort_order'       => 2,
            'is_active'        => true,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
