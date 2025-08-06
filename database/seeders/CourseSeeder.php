<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = CourseCategory::all();
        $teachers = Teacher::all();

        $courses = [
            [
                'title' => 'কুরআন তিলাওয়াত ও তাজবীদ শিক্ষা',
                'description' => 'পবিত্র কুরআন সুন্দরভাবে তিলাওয়াত করার জন্য তাজবীদের নিয়মকানুন শিখুন। এই কোর্সে আপনি শিখবেন সঠিক উচ্চারণ, মাখরাজ, এবং তিলাওয়াতের আদব-কায়দা।',
                'category' => 'কুরআন শিক্ষা',
                'level' => 'beginner',
                'price' => 0,
                'is_featured' => true,
                'is_free' => true,
            ],
            [
                'title' => 'আরবি ভাষা শিক্ষা - প্রাথমিক পর্যায়',
                'description' => 'আরবি ভাষার বর্ণমালা থেকে শুরু করে মৌলিক ব্যাকরণ শিখুন। কুরআন বুঝার জন্য প্রয়োজনীয় আরবি শব্দভাণ্ডার গড়ে তুলুন।',
                'category' => 'আরবি ভাষা',
                'level' => 'beginner',
                'price' => 1500,
                'is_featured' => true,
            ],
            [
                'title' => 'হাদিস শরীফ অধ্যয়ন',
                'description' => 'রাসূল (সা.) এর পবিত্র হাদিস সমূহের অর্থ ও ব্যাখ্যা জানুন। বুখারী, মুসলিম সহ বিখ্যাত হাদিস গ্রন্থসমূহ থেকে নির্বাচিত হাদিস।',
                'category' => 'হাদিস শরীফ',
                'level' => 'intermediate',
                'price' => 2000,
                'is_featured' => true,
            ],
            [
                'title' => 'ইসলামী ফিকহ ও আইনশাস্ত্র',
                'description' => 'দৈনন্দিন জীবনে ইসলামী আইনের প্রয়োগ ও মাসায়েল শিখুন। পবিত্রতা, নামাজ, রোজা, হজ্জ, ও লেনদেনের বিধিবিধান।',
                'category' => 'ফিকহ ও আইন',
                'level' => 'intermediate',
                'price' => 2500,
            ],
            [
                'title' => 'সীরাতুন্নবী (সা.) - নবীজীবনী',
                'description' => 'মহানবী হযরত মুহাম্মদ (সা.) এর জীবনী ও আদর্শ জানুন। মক্কী ও মাদানী জীবনের বিস্তারিত আলোচনা।',
                'category' => 'ইসলামের ইতিহাস',
                'level' => 'beginner',
                'price' => 1000,
                'is_featured' => true,
            ],
            [
                'title' => 'ইসলামী আকীদা ও বিশ্বাস',
                'description' => 'ইসলামের মৌলিক বিশ্বাস ও আকীদা সম্পর্কে বিস্তারিত জানুন। তাওহীদ, রিসালাত, আখিরাত সম্পর্কে সম্যক ধারণা।',
                'category' => 'আকীদা ও বিশ্বাস',
                'level' => 'beginner',
                'price' => 800,
            ],
            [
                'title' => 'হজ্জ ও উমরাহ সম্পূর্ণ গাইড',
                'description' => 'হজ্জ ও উমরাহর সম্পূর্ণ নিয়মকানুন ও দোয়া-দুরুদ শিখুন। ব্যবহারিক পরামর্শ ও প্রস্তুতির দিকনির্দেশনা।',
                'category' => 'ফিকহ ও আইন',
                'level' => 'intermediate',
                'price' => 1800,
            ],
            [
                'title' => 'ইসলামী পারিবারিক জীবন',
                'description' => 'ইসলামী শিক্ষা অনুযায়ী পারিবারিক জীবন পরিচালনার উপায়। স্বামী-স্ত্রীর অধিকার ও কর্তব্য, সন্তান লালন-পালনের আদব।',
                'category' => 'ফিকহ ও আইন',
                'level' => 'beginner',
                'price' => 1200,
            ],
            [
                'title' => 'জাকাত ও দান-সদকার নিয়মাবলী',
                'description' => 'জাকাত ও বিভিন্ন প্রকার দানের ইসলামী নিয়মকানুন। কোন সম্পদে কত জাকাত, কাকে দেওয়া যাবে - বিস্তারিত আলোচনা।',
                'category' => 'ইসলামী অর্থনীতি',
                'level' => 'intermediate',
                'price' => 1500,
            ],
            [
                'title' => 'দোয়া ও জিকির সংকলন',
                'description' => 'দৈনন্দিন জীবনের গুরুত্বপূর্ণ দোয়া ও জিকির শিখুন। সকাল-সন্ধ্যার আমল, খাওয়া-দাওয়ার দোয়া, ভ্রমণের দোয়া।',
                'category' => 'দাওয়াহ ও তাবলীগ',
                'level' => 'beginner',
                'price' => 0,
                'is_free' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            $category = $categories->where('name', $courseData['category'])->first();
            $teacher = $teachers->random();

            Course::create([
                'title' => $courseData['title'],
                'slug' => Str::slug($courseData['title']),
                'full_description' => $courseData['description'],
                'category_id' => $category->id,
                'level' => $courseData['level'],
                'instructor_id' => $teacher->id,
                'thumbnail_image' => 'courses/thumbnails/default.jpg',
                'price' => $courseData['price'],
                'currency' => 'BDT',
                'discount_price' => $courseData['price'] > 0 ? $courseData['price'] * 0.8 : null,
                'discount_expires_at' => now()->addMonths(2),
                'duration_in_minutes' => rand(300, 1200),
                'status' => 'published',
                'is_featured' => $courseData['is_featured'] ?? false,
                'is_free' => $courseData['is_free'] ?? false,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Add multiple instructors to some courses
        $courses = Course::published()->take(5)->get();
        $availableTeachers = Teacher::take(10)->get();

        foreach ($courses as $course) {
            // Add 1-3 additional instructors
            $additionalInstructors = $availableTeachers->random(rand(1, 3));
            
            foreach ($additionalInstructors as $index => $instructor) {
                $course->instructors()->attach($instructor->id, [
                    'role' => 'secondary',
                    'bio' => 'অভিজ্ঞ শিক্ষক ও ইসলামী শিক্ষায় বিশেষজ্ঞ।',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}