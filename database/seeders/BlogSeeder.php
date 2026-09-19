<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'The beast team around and how we make it work',
                'image' => 'assets/img/update2/blog/blog_1_1.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-05',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ],
            [
                'title' => 'Interior design is the Art and science Of Design',
                'image' => 'assets/img/update2/blog/blog_1_2.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-06',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ],
            [
                'title' => 'Redefining Organizational Dynamics by Embracing',
                'image' => 'assets/img/update2/blog/blog_1_3.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-07',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ],
            [
                'title' => 'The beast team around and how we make it work 2',
                'image' => 'assets/img/update2/blog/blog_1_1.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-09',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ],
            [
                'title' => 'Interior design is the Art and science Of Design 2',
                'image' => 'assets/img/update2/blog/blog_1_2.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-10',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ],
            [
                'title' => 'Redefining Organizational Dynamics by Embracing 2',
                'image' => 'assets/img/update2/blog/blog_1_3.jpg',
                'author' => 'Bondhon',
                'category' => 'INDUSTRY',
                'published_at' => '2024-07-12',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.',
            ]
        ];

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'slug' => Str::slug($blog['title']),
                'image' => $blog['image'],
                'author' => $blog['author'],
                'category' => $blog['category'],
                'published_at' => $blog['published_at'],
                'content' => $blog['content'],
                'status' => true,
            ]);
        }
    }
}
