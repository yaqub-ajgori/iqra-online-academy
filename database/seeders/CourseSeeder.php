<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = CourseCategory::pluck('id')->toArray();
        $teachers = Teacher::pluck('id')->toArray();

        $courses = [
            [
                'title' => 'Laravel for Beginners',
                'full_description' => 'Learn the basics of Laravel framework.',
                'level' => 'beginner',
                'price' => 1000,
                'currency' => 'BDT',
                'discount_price' => 800,
                'discount_expires_at' => Carbon::now()->addDays(10),
                'duration_hours' => 20,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
            ],
            [
                'title' => 'Advanced Mathematics',
                'full_description' => 'Deep dive into advanced math topics.',
                'level' => 'advanced',
                'price' => 1200,
                'currency' => 'BDT',
                'discount_price' => 1000,
                'discount_expires_at' => Carbon::now()->addDays(15),
                'duration_hours' => 30,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
            ],
            [
                'title' => 'Physics Essentials',
                'full_description' => 'Master the essentials of physics.',
                'level' => 'intermediate',
                'price' => 900,
                'currency' => 'BDT',
                'discount_price' => 700,
                'discount_expires_at' => Carbon::now()->addDays(7),
                'duration_hours' => 15,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
            ],
            [
                'title' => 'Spoken English',
                'full_description' => 'Improve your English speaking skills.',
                'level' => 'beginner',
                'price' => 500,
                'currency' => 'BDT',
                'discount_price' => 400,
                'discount_expires_at' => Carbon::now()->addDays(5),
                'duration_hours' => 10,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
            ],
            [
                'title' => 'Business 101',
                'full_description' => 'Introduction to business concepts.',
                'level' => 'beginner',
                'price' => 700,
                'currency' => 'BDT',
                'discount_price' => 600,
                'discount_expires_at' => Carbon::now()->addDays(12),
                'duration_hours' => 12,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => true,
            ],
        ];

        foreach ($courses as $i => $data) {
            $data['slug'] = Str::slug($data['title']);
            $data['category_id'] = $categories[$i % count($categories)] ?? $categories[0] ?? 1;
            $data['instructor_id'] = $teachers[$i % count($teachers)] ?? $teachers[0] ?? 1;
            $data['thumbnail_image'] = null;
            $data['published_at'] = now();
            Course::create($data);
        }
    }
} 