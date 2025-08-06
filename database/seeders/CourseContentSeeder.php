<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use Illuminate\Database\Seeder;

class CourseContentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create 3-5 modules per course
            $moduleCount = rand(3, 5);
            
            for ($i = 1; $i <= $moduleCount; $i++) {
                $moduleNames = [
                    'ভূমিকা ও প্রাথমিক ধারণা',
                    'মৌলিক বিষয়সমূহ',
                    'বিস্তারিত আলোচনা',
                    'প্রয়োগিক দিকসমূহ',
                    'উদাহরণ ও ব্যাখ্যা',
                    'বাস্তব প্রয়োগ',
                    'পরীক্ষা ও মূল্যায়ন',
                    'সমাপনী ও পর্যালোচনা'
                ];

                $module = CourseModule::create([
                    'course_id' => $course->id,
                    'title' => "অধ্যায় {$i}: " . $moduleNames[$i - 1],
                    'sort_order' => $i,
                ]);

                // Create 3-6 lessons per module
                $lessonCount = rand(3, 6);
                
                for ($j = 1; $j <= $lessonCount; $j++) {
                    $lessonTypes = ['video', 'text', 'pdf'];
                    $lessonType = $lessonTypes[array_rand($lessonTypes)];
                    
                    $lessonTitles = [
                        'পরিচিতি ও মূল বিষয়',
                        'তত্ত্বীয় ভিত্তি',
                        'ব্যাখ্যা ও বিশ্লেষণ',
                        'উদাহরণ ও প্রয়োগ',
                        'অনুশীলনী',
                        'পর্যালোচনা ও সারাংশ'
                    ];

                    $lessonContent = [
                        'এই পাঠে আপনি শিখবেন মূল বিষয়গুলি সম্পর্কে।',
                        'বিস্তারিত ব্যাখ্যা ও উদাহরণের মাধ্যমে বিষয়টি বুঝানো হয়েছে।',
                        'প্রয়োগিক দিক ও বাস্তব জীবনে এর ব্যবহার দেখানো হয়েছে।',
                        'বিভিন্ন প্রশ্নের উত্তর ও সমাধানের পদ্ধতি আলোচনা করা হয়েছে।'
                    ];

                    CourseLesson::create([
                        'course_id' => $course->id,
                        'module_id' => $module->id,
                        'title' => "পাঠ {$j}: " . $lessonTitles[($j - 1) % count($lessonTitles)],
                        'content' => $lessonContent[($j - 1) % count($lessonContent)],
                        'lesson_type' => $lessonType,
                        'video_url' => $lessonType === 'video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
                        'video_duration' => $lessonType === 'video' ? rand(300, 1800) : null,
                        'attachments' => $lessonType === 'pdf' ? json_encode(['lesson-notes.pdf', 'exercise.pdf']) : null,
                        'resources' => json_encode(['https://example.com/resource1', 'https://example.com/resource2']),
                        'primary_file_path' => $lessonType === 'pdf' ? 'lessons/sample-lesson.pdf' : null,
                        'primary_file_type' => $lessonType === 'pdf' ? 'pdf' : null,
                        'primary_file_size' => $lessonType === 'pdf' ? rand(100000, 2000000) : null,
                        'is_preview' => $i === 1 && $j <= 2, // First 2 lessons of first module are preview
                        'is_mandatory' => true,
                        'requires_completion' => true,
                        'minimum_time_seconds' => rand(120, 600),
                        'sort_order' => $j,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}