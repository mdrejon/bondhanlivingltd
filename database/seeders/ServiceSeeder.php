<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Commercial
            ['title' => 'Building Construction', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_1.svg', 'description' => 'Certainly, I can provide some general details about the construction industry. If you have a specific aspect.'],
            ['title' => 'Interior Designing', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_2.svg', 'description' => 'Construction services also encompass renovating and existing structures to update them change layout.'],
            ['title' => 'General Contracting', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_3.svg', 'description' => 'This includes building homes, apartments, and housing units. It can involve single-family homes and more.'],
            ['title' => 'Architecture Design', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_4.svg', 'description' => 'Architectural wonders await firms offer project Our management services. Along with design architecture firms.'],
            ['title' => 'House Renovation', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_5.svg', 'description' => 'Where ideas take shape architecture often offer project in Our management services used in and around a home.'],
            ['title' => 'Material Supply', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_6.svg', 'description' => 'Material supply can refer to the process of providing and delivering various materials needed for a project.'],
            ['title' => 'Project Management', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_1.svg', 'description' => 'We offer comprehensive project management to ensure your construction stays on schedule and within budget.'],
            ['title' => 'Structural Engineering', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_2.svg', 'description' => 'Our structural engineering services ensure safety, durability, and compliance with all building codes and standards.'],
            ['title' => 'Land Development', 'category' => 'Commercial', 'icon' => 'assets/img/update2/icon/service_1_3.svg', 'description' => 'From site preparation to final grading, we handle every aspect of land development with precision.'],

            // Residential
            ['title' => 'Home Building', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_1.svg', 'description' => 'We build custom homes that reflect your lifestyle, from single-family residences to luxury estates.'],
            ['title' => 'Apartment Construction', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_2.svg', 'description' => 'We specialize in multi-unit residential buildings designed for comfort, functionality, and modern living.'],
            ['title' => 'Interior Fit-Out', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_3.svg', 'description' => 'Our interior fit-out services transform bare spaces into beautifully designed and fully functional living areas.'],
            ['title' => 'Roofing & Waterproofing', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_4.svg', 'description' => 'We provide durable roofing and waterproofing solutions to protect your home from the elements for years to come.'],
            ['title' => 'Plumbing & Electrical', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_5.svg', 'description' => 'Complete plumbing and electrical systems installed by certified professionals ensuring safety and compliance.'],
            ['title' => 'Landscaping', 'category' => 'Residential', 'icon' => 'assets/img/update2/icon/service_1_6.svg', 'description' => 'We create stunning outdoor environments that complement your home and enhance your property\'s overall value.'],

            // Industrial
            ['title' => 'Factory Construction', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_1.svg', 'description' => 'We design and build efficient factory buildings that meet operational needs and industry safety standards.'],
            ['title' => 'Warehouse Building', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_2.svg', 'description' => 'Our warehouse construction solutions deliver spacious, durable, and cost-effective storage facilities.'],
            ['title' => 'Civil Infrastructure', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_3.svg', 'description' => 'We develop roads, bridges, and civil infrastructure that support industrial operations and community growth.'],
            ['title' => 'Steel Structure Works', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_4.svg', 'description' => 'Precision-engineered steel frameworks for industrial facilities, offering strength and long-term reliability.'],
            ['title' => 'Industrial Renovation', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_5.svg', 'description' => 'We upgrade and modernize existing industrial facilities to enhance productivity, efficiency, and safety.'],
            ['title' => 'Utility Installation', 'category' => 'Industrial', 'icon' => 'assets/img/update2/icon/service_1_6.svg', 'description' => 'Complete utility systems including power, water, and waste management for industrial complexes.'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['title' => $service['title']],
                [
                    'slug' => Str::slug($service['title']),
                    'category' => $service['category'],
                    'icon' => $service['icon'],
                    'short_description' => $service['description'],
                    'description' => $service['description'],
                    'image' => 'assets/img/service/service-details-1-1.jpg',
                    'status' => 1,
                    'seo_title' => $service['title'],
                ]
            );
        }
        
        $this->command->info("Services seeded successfully.");
    }
}
