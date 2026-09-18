<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Michel Carlos',
                'designation' => 'Architect',
                'text' => 'Real estate construction companies may also engage in sales & marketing activities to promote their developed properties. This includes creating marketing strategies.',
                'rating' => 5,
                'image' => 'assets/img/testimonial/testi_2_1.jpg',
                'status' => true,
            ],
            [
                'name' => 'Anjelina Rose',
                'designation' => 'Contructor',
                'text' => 'Demolition companies handle the safe and controlled removal of existing structures, making way for new construction. This includes large-scale projects such as roads, bridges etc.',
                'rating' => 5,
                'image' => 'assets/img/testimonial/testi_2_2.jpg',
                'status' => true,
            ],
            [
                'name' => 'Alex Jordan',
                'designation' => 'UI/UX Designer',
                'text' => 'These services involve activities before the actual construction begins, including site analysis, feasibility studies, cost estimation, and more. Various software tools assist.',
                'rating' => 5,
                'image' => 'assets/img/testimonial/testi_2_3.jpg',
                'status' => true,
            ],
        ];

        foreach ($testimonials as $item) {
            \App\Models\Testimonial::create($item);
        }
    }
}
