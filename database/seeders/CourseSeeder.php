<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quranCategory = CourseCategory::where('slug', 'quran')->first();
        $hadithCategory = CourseCategory::where('slug', 'hadith')->first();
        $fiqhCategory = CourseCategory::where('slug', 'fiqh')->first();
        $tajweedSubcat = CourseCategory::where('slug', 'tajweed')->first();
        
        $rahman = Teacher::whereHas('user', function($q) {
            $q->where('email', 'rahman@iqra-academy.com');
        })->first();
        
        $karim = Teacher::whereHas('user', function($q) {
            $q->where('email', 'karim@iqra-academy.com');
        })->first();
        
        $hasan = Teacher::whereHas('user', function($q) {
            $q->where('email', 'hasan@iqra-academy.com');
        })->first();

        $courses = [
            [
                'title' => 'কুরআন তিলাওয়াত শিক্ষা - শুরু থেকে শেষ',
                'slug' => 'quran-tilawat-basic',
                'description' => 'কুরআন তিলাওয়াতের মৌলিক নিয়মকানুন ও সঠিক উচ্চারণ শিখুন।',
                'full_description' => 'এই কোর্সে আপনি কুরআন তিলাওয়াতের সম্পূর্ণ নিয়মকানুন শিখবেন। আরবি বর্ণমালা থেকে শুরু করে তাজবীদের সকল নিয়ম পর্যন্ত বিস্তারিত আলোচনা।',
                'category_id' => $quranCategory->id,
                'subcategory_id' => $tajweedSubcat->id,
                'level' => 'beginner',
                'language' => 'bengali',
                'instructor_id' => $rahman->id,
                'thumbnail_image' => '/images/courses/quran-basic.jpg',
                'preview_video_url' => 'https://youtube.com/watch?v=sample1',
                'learning_outcomes' => [
                    'আরবি বর্ণমালা সঠিকভাবে পড়তে পারবেন',
                    'তাজবীদের মৌলিক নিয়মগুলো জানবেন',
                    'কুরআন শুদ্ধভাবে তিলাওয়াত করতে পারবেন',
                    'দৈনিক নামাজের জন্য প্রয়োজনীয় সূরা পড়তে পারবেন'
                ],
                'requirements' => [
                    'কোন পূর্ব অভিজ্ঞতার প্রয়োজন নেই',
                    'ইন্টারনেট সংযোগ',
                    'শেখার আগ্রহ ও ধৈর্য'
                ],
                'price' => 2500.00,
                'currency' => 'BDT',
                'discount_price' => 1999.00,
                'discount_expires_at' => now()->addDays(30),
                'duration_hours' => 40,
                'total_lessons' => 25,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
                'average_rating' => 4.9,
                'total_reviews' => 156,
                'published_at' => now(),
            ],
            [
                'title' => 'তাজবীদের নিয়মকানুন - উন্নত কোর্স',
                'slug' => 'advanced-tajweed',
                'description' => 'তাজবীদের উন্নত নিয়মকানুন ও সূক্ষ্ম বিষয়গুলো বিস্তারিত আলোচনা।',
                'full_description' => 'যারা মৌলিক তাজবীদ জানেন তাদের জন্য উন্নত পর্যায়ের কোর্স। মাদ, ওয়াকফ, ইখফা ইত্যাদি বিষয়ে গভীর জ্ঞান অর্জন করুন।',
                'category_id' => $quranCategory->id,
                'subcategory_id' => $tajweedSubcat->id,
                'level' => 'advanced',
                'language' => 'bengali',
                'instructor_id' => $rahman->id,
                'thumbnail_image' => '/images/courses/advanced-tajweed.jpg',
                'preview_video_url' => 'https://youtube.com/watch?v=sample2',
                'learning_outcomes' => [
                    'তাজবীদের সকল উন্নত নিয়মকানুন আয়ত্ত করবেন',
                    'ক্বিরাতের বিভিন্ন ধরন জানবেন',
                    'ওয়াকফ ও ইবতিদার নিয়মগুলো প্রয়োগ করতে পারবেন'
                ],
                'requirements' => [
                    'মৌলিক তাজবীদের জ্ঞান থাকতে হবে',
                    'কুরআন পড়তে পারতে হবে',
                    'কমপক্ষে ৬ মাসের কুরআন পড়ার অভিজ্ঞতা'
                ],
                'price' => 3500.00,
                'currency' => 'BDT',
                'duration_hours' => 60,
                'total_lessons' => 35,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
                'average_rating' => 4.8,
                'total_reviews' => 89,
                'published_at' => now(),
            ],
            [
                'title' => 'হাদিস শরীফ - সহীহ বুখারী নির্বাচিত অধ্যায়',
                'slug' => 'sahih-bukhari-selected',
                'description' => 'সহীহ বুখারী থেকে নির্বাচিত গুরুত্বপূর্ণ হাদিস ও তার ব্যাখ্যা।',
                'full_description' => 'ইমাম বুখারী (রহ.) এর বিখ্যাত হাদিস গ্রন্থ থেকে দৈনন্দিন জীবনের জন্য প্রয়োজনীয় হাদিসগুলোর বিস্তারিত আলোচনা।',
                'category_id' => $hadithCategory->id,
                'level' => 'intermediate',
                'language' => 'bengali',
                'instructor_id' => $karim->id,
                'thumbnail_image' => '/images/courses/sahih-bukhari.jpg',
                'preview_video_url' => 'https://youtube.com/watch?v=sample3',
                'learning_outcomes' => [
                    'হাদিসের পরিচয় ও গুরুত্ব জানবেন',
                    'ইবাদত বিষয়ক গুরুত্বপূর্ণ হাদিস জানবেন',
                    'আখলাক ও চরিত্র গঠনে হাদিসের নির্দেশনা পাবেন'
                ],
                'requirements' => [
                    'ইসলামের মৌলিক জ্ঞান থাকতে হবে',
                    'আরবি পড়তে পারলে ভাল হবে (বাধ্যতামূলক নয়)'
                ],
                'price' => 3000.00,
                'currency' => 'BDT',
                'discount_price' => 2299.00,
                'discount_expires_at' => now()->addDays(15),
                'duration_hours' => 50,
                'total_lessons' => 30,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'average_rating' => 4.7,
                'total_reviews' => 67,
                'published_at' => now(),
            ],
            [
                'title' => 'ইসলামিক ফিকহ - পবিত্রতা ও নামাজ',
                'slug' => 'fiqh-taharat-salah',
                'description' => 'পবিত্রতা ও নামাজের ফিকহী মাসায়েল বিস্তারিত আলোচনা।',
                'full_description' => 'দৈনন্দিন জীবনে প্রয়োজনীয় পবিত্রতা ও নামাজের সকল মাসায়েল কুরআন ও সুন্নাহর আলোকে আলোচনা।',
                'category_id' => $fiqhCategory->id,
                'level' => 'beginner',
                'language' => 'bengali',
                'instructor_id' => $hasan->id,
                'thumbnail_image' => '/images/courses/fiqh-basic.jpg',
                'preview_video_url' => 'https://youtube.com/watch?v=sample4',
                'learning_outcomes' => [
                    'পবিত্রতার সকল নিয়মকানুন জানবেন',
                    'নামাজের সঠিক পদ্ধতি শিখবেন',
                    'বিভিন্ন পরিস্থিতিতে নামাজের হুকুম জানবেন'
                ],
                'requirements' => [
                    'ইসলামের মৌলিক বিশ্বাস',
                    'পড়া-লেখা জানা'
                ],
                'price' => 0.00,
                'currency' => 'BDT',
                'duration_hours' => 30,
                'total_lessons' => 20,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => true,
                'average_rating' => 4.9,
                'total_reviews' => 234,
                'published_at' => now(),
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}
