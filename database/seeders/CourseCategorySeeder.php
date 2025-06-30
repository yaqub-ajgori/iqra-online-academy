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
                'icon' => 'book-open',
                'color' => '#2d5a27',
                'sort_order' => 1,
            ],
            [
                'name' => 'হাদিস',
                'slug' => 'hadith',
                'description' => 'হাদিস শরীফ, সুন্নাহ এবং রাসূলের জীবনী সম্পর্কিত কোর্স',
                'icon' => 'scroll',
                'color' => '#5f5fcd',
                'sort_order' => 2,
            ],
            [
                'name' => 'ফিকহ',
                'slug' => 'fiqh',
                'description' => 'ইসলামিক আইন, দৈনন্দিন মাসায়েল এবং ইবাদত সম্পর্কিত কোর্স',
                'icon' => 'scales',
                'color' => '#d4a574',
                'sort_order' => 3,
            ],
            [
                'name' => 'আকিদা',
                'slug' => 'aqidah',
                'description' => 'ইসলামিক বিশ্বাস, তাওহিদ এবং আধ্যাত্মিকতা সম্পর্কিত কোর্স',
                'icon' => 'heart',
                'color' => '#2d5a27',
                'sort_order' => 4,
            ],
            [
                'name' => 'সীরাত',
                'slug' => 'sirah',
                'description' => 'রাসূল (সা.) এর জীবনী এবং ইসলামের ইতিহাস সম্পর্কিত কোর্স',
                'icon' => 'user',
                'color' => '#5f5fcd',
                'sort_order' => 5,
            ],
            [
                'name' => 'আরবি ভাষা',
                'slug' => 'arabic-language',
                'description' => 'আরবি ভাষা শেখা, ব্যাকরণ এবং সাহিত্য সম্পর্কিত কোর্স',
                'icon' => 'languages',
                'color' => '#d4a574',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }

        // Create subcategories for Quran
        $quranCategory = CourseCategory::where('slug', 'quran')->first();
        $quranSubcategories = [
            [
                'name' => 'তাজবীদ',
                'slug' => 'tajweed',
                'description' => 'কুরআন তিলাওয়াতের সঠিক নিয়মকানুন',
                'parent_id' => $quranCategory->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'হিফজ',
                'slug' => 'hifz',
                'description' => 'কুরআন মুখস্থ করার পদ্ধতি',
                'parent_id' => $quranCategory->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'তাফসীর',
                'slug' => 'tafseer',
                'description' => 'কুরআনের ব্যাখ্যা ও অর্থ',
                'parent_id' => $quranCategory->id,
                'sort_order' => 3,
            ],
        ];

        foreach ($quranSubcategories as $subcategory) {
            CourseCategory::create($subcategory);
        }
    }
}
