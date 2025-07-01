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
        
        // Find teachers by their full names
        $rahman = Teacher::where('full_name', 'উস্তাদ মোহাম্মদ রহমান')->first();
        $karim = Teacher::where('full_name', 'উস্তাদ আবদুল কারিম')->first();
        $hasan = Teacher::where('full_name', 'উস্তাদ মোহাম্মদ হাসান')->first();

        $courses = [
            [
                'title' => 'কুরআন তিলাওয়াত শিক্ষা - শুরু থেকে শেষ',
                'slug' => 'quran-tilawat-basic',
                'full_description' => 'এই কোর্সে আপনি কুরআন তিলাওয়াতের সম্পূর্ণ নিয়মকানুন শিখবেন। আরবি বর্ণমালা থেকে শুরু করে তাজবীদের সকল নিয়ম পর্যন্ত বিস্তারিত আলোচনা।',
                'category_id' => $quranCategory->id,
                'level' => 'beginner',
                'instructor_id' => $rahman->id,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=400&h=250&fit=crop',
                'price' => 2500.00,
                'currency' => 'BDT',
                'discount_price' => 1999.00,
                'discount_expires_at' => now()->addDays(30),
                'duration_hours' => 40,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
                'published_at' => now(),
            ],
            [
                'title' => 'তাজবীদের নিয়মকানুন - উন্নত কোর্স',
                'slug' => 'advanced-tajweed',
                'full_description' => 'যারা মৌলিক তাজবীদ জানেন তাদের জন্য উন্নত পর্যায়ের কোর্স। মাদ, ওয়াকফ, ইখফা ইত্যাদি বিষয়ে গভীর জ্ঞান অর্জন করুন।',
                'category_id' => $quranCategory->id,
                'level' => 'advanced',
                'instructor_id' => $rahman->id,
                'thumbnail_image' => null, // No image - will show placeholder
                'price' => 3500.00,
                'currency' => 'BDT',
                'duration_hours' => 60,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => false,
                'published_at' => now(),
            ],
            [
                'title' => 'হাদিস শরীফ - সহীহ বুখারী নির্বাচিত অধ্যায়',
                'slug' => 'sahih-bukhari-selected',
                'full_description' => 'ইমাম বুখারী (রহ.) এর বিখ্যাত হাদিস গ্রন্থ থেকে দৈনন্দিন জীবনের জন্য প্রয়োজনীয় হাদিসগুলোর বিস্তারিত আলোচনা।',
                'category_id' => $hadithCategory->id,
                'level' => 'intermediate',
                'instructor_id' => $karim->id,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=250&fit=crop',
                'price' => 3000.00,
                'currency' => 'BDT',
                'discount_price' => 2299.00,
                'discount_expires_at' => now()->addDays(15),
                'duration_hours' => 50,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'published_at' => now(),
            ],
            [
                'title' => 'ইসলামিক ফিকহ - পবিত্রতা ও নামাজ',
                'slug' => 'fiqh-taharat-salah',
                'full_description' => 'দৈনন্দিন জীবনে প্রয়োজনীয় পবিত্রতা ও নামাজের সকল মাসায়েল কুরআন ও সুন্নাহর আলোকে আলোচনা।',
                'category_id' => $fiqhCategory->id,
                'level' => 'beginner',
                'instructor_id' => $hasan->id,
                'thumbnail_image' => '', // Empty string - will show placeholder
                'price' => 0.00,
                'currency' => 'BDT',
                'duration_hours' => 30,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'আরবি ভাষা শিক্ষা - মৌলিক কোর্স',
                'slug' => 'arabic-language-basic',
                'full_description' => 'আরবি ভাষার মৌলিক শিক্ষা। বর্ণমালা, উচ্চারণ, ব্যাকরণ এবং দৈনন্দিন কথোপকথন শিখুন।',
                'category_id' => $quranCategory->id,
                'level' => 'beginner',
                'instructor_id' => $karim->id,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=400&h=250&fit=crop',
                'price' => 2000.00,
                'currency' => 'BDT',
                'duration_hours' => 35,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'published_at' => now(),
            ],
            [
                'title' => 'সীরাতুন্নবী (সা:) - নবীর জীবনী',
                'slug' => 'seerat-un-nabi',
                'full_description' => 'রাসূলুল্লাহ (সা:) এর পূর্ণাঙ্গ জীবনী। মক্কী ও মাদানী জীবনের সকল গুরুত্বপূর্ণ ঘটনা।',
                'category_id' => $hadithCategory->id,
                'level' => 'intermediate',
                'instructor_id' => $hasan->id,
                'thumbnail_image' => null, // No image - will show placeholder
                'price' => 1800.00,
                'currency' => 'BDT',
                'duration_hours' => 45,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'published_at' => now(),
            ],
            [
                'title' => 'আকিদা শিক্ষা - ঈমানের ভিত্তি',
                'slug' => 'aqeedah-foundation',
                'full_description' => 'ইসলামিক আকিদার মৌলিক শিক্ষা। তাওহীদ, রিসালাত, আখিরাত ইত্যাদি বিষয়ে বিস্তারিত আলোচনা।',
                'category_id' => $fiqhCategory->id,
                'level' => 'beginner',
                'instructor_id' => $rahman->id,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400&h=250&fit=crop',
                'price' => 0.00,
                'currency' => 'BDT',
                'duration_hours' => 25,
                'status' => 'published',
                'is_featured' => true,
                'is_free' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'ইসলামিক অর্থনীতি - জাকাত ও সদকা',
                'slug' => 'islamic-economics',
                'full_description' => 'ইসলামিক অর্থনীতির মৌলিক নীতি। জাকাত, সদকা, রিবা মুক্ত ব্যাংকিং ইত্যাদি বিষয়ে আলোচনা।',
                'category_id' => $fiqhCategory->id,
                'level' => 'advanced',
                'instructor_id' => $karim->id,
                'thumbnail_image' => '', // Empty string - will show placeholder
                'price' => 4000.00,
                'currency' => 'BDT',
                'duration_hours' => 55,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'published_at' => now(),
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}
