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

        $banglaDescription = 'এই কোর্সটি লারাভেল ফ্রেমওয়ার্কের মৌলিক ধারণা এবং ব্যবহার শেখায়। এটি নতুন শিক্ষার্থীদের জন্য উপযুক্ত যারা ওয়েব ডেভেলপমেন্টে আগ্রহী। কোর্সে অন্তর্ভুক্ত রয়েছে লারাভেল ইনস্টলেশন, রাউটিং, কন্ট্রোলার, ভিউ এবং ডাটাবেস ইন্টিগ্রেশন।';
        $courses = [
            [
                'title' => 'Laravel for Beginners',
                'full_description' => $banglaDescription,
                'level' => 'beginner',
                'price' => 1000,
                'currency' => 'BDT',
                'discount_price' => 800,
                'discount_expires_at' => Carbon::now()->addDays(10),
                'duration_in_minutes' => 1200,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
            ],
            [
                'title' => 'Advanced Mathematics',
                'full_description' => $banglaDescription,
                'level' => 'advanced',
                'price' => 1200,
                'currency' => 'BDT',
                'discount_price' => 1000,
                'discount_expires_at' => Carbon::now()->addDays(15),
                'duration_in_minutes' => 1800,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
            ],
            [
                'title' => 'Physics Essentials',
                'full_description' => $banglaDescription,
                'level' => 'intermediate',
                'price' => 900,
                'currency' => 'BDT',
                'discount_price' => 700,
                'discount_expires_at' => Carbon::now()->addDays(7),
                'duration_in_minutes' => 900,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
            ],
            [
                'title' => 'Spoken English',
                'full_description' => $banglaDescription,
                'level' => 'beginner',
                'price' => 500,
                'currency' => 'BDT',
                'discount_price' => 400,
                'discount_expires_at' => Carbon::now()->addDays(5),
                'duration_in_minutes' => 600,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
            ],
            [
                'title' => 'Business 101',
                'full_description' => $banglaDescription,
                'level' => 'beginner',
                'price' => 700,
                'currency' => 'BDT',
                'discount_price' => 600,
                'discount_expires_at' => Carbon::now()->addDays(12),
                'duration_in_minutes' => 720,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => true,
            ],
        ];

        foreach ($courses as $i => $data) {
            $data['slug'] = Str::slug($data['title']);
            $data['category_id'] = $categories[$i % count($categories)] ?? $categories[0] ?? 1;
            $data['instructor_id'] = $teachers[$i % count($teachers)] ?? $teachers[0] ?? 1;
            $data['thumbnail_image'] = null;
            $data['published_at'] = now();
            $course = Course::create($data);

            // Create modules and lessons for each course
            for ($j = 1; $j <= 3; $j++) {
                $module = $course->modules()->create([
                    'title' => "Module $j: " . Str::title(fake()->words(3, true)),
                    'sort_order' => $j,
                ]);

                for ($k = 1; $k <= 5; $k++) {
                    $module->lessons()->create([
                        'course_id' => $course->id,
                        'title' => "Lesson $k: " . Str::title(fake()->sentence(4)),
                        'lesson_type' => 'video',
                        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'video_duration' => fake()->numberBetween(300, 1200), // Duration in seconds (5-20 minutes)
                        'is_preview' => $k === 1,
                        'sort_order' => $k,
                    ]);
                }
            }
        }
    }
} 