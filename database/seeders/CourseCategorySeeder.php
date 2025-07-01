<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'কুরআন',
                'slug' => 'quran',
                'description' => 'কুরআন তিলাওয়াত, তাজবীদ, হিফজ এবং তাফসীর সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
            [
                'name' => 'তাজবীদ',
                'slug' => 'tajweed',
                'description' => 'কুরআন তিলাওয়াতের সঠিক নিয়মকানুন',
                'is_active' => true,
            ],
            [
                'name' => 'হিফজ',
                'slug' => 'hifz',
                'description' => 'কুরআন মুখস্থ করার পদ্ধতি',
                'is_active' => true,
            ],
            [
                'name' => 'তাফসীর',
                'slug' => 'tafseer',
                'description' => 'কুরআনের ব্যাখ্যা ও অর্থ',
                'is_active' => true,
            ],
            [
                'name' => 'হাদিস',
                'slug' => 'hadith',
                'description' => 'হাদিস শরীফ, সুন্নাহ এবং রাসূলের জীবনী সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
            [
                'name' => 'ফিকহ',
                'slug' => 'fiqh',
                'description' => 'ইসলামিক আইন, দৈনন্দিন মাসায়েল এবং ইবাদত সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
            [
                'name' => 'আকিদা',
                'slug' => 'aqidah',
                'description' => 'ইসলামিক বিশ্বাস, তাওহিদ এবং আধ্যাত্মিকতা সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
            [
                'name' => 'সীরাত',
                'slug' => 'sirah',
                'description' => 'রাসূল (সা.) এর জীবনী এবং ইসলামের ইতিহাস সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
            [
                'name' => 'আরবি ভাষা',
                'slug' => 'arabic-language',
                'description' => 'আরবি ভাষা শেখা, ব্যাকরণ এবং সাহিত্য সম্পর্কিত কোর্স',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
