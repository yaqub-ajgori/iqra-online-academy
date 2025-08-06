<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'কুরআন শিক্ষা',
                'description' => 'পবিত্র কুরআন তিলাওয়াত, হিফজ ও তাজবীদ শিক্ষা',
                'sort_order' => 1,
            ],
            [
                'name' => 'হাদিস শরীফ',
                'description' => 'রাসূল (সা.) এর হাদিস অধ্যয়ন ও ব্যাখ্যা',
                'sort_order' => 2,
            ],
            [
                'name' => 'ফিকহ ও আইন',
                'description' => 'ইসলামী ফিকহ ও শরীয়াহ আইন শিক্ষা',
                'sort_order' => 3,
            ],
            [
                'name' => 'আরবি ভাষা',
                'description' => 'আরবি ভাষা শিক্ষা ও সাহিত্য',
                'sort_order' => 4,
            ],
            [
                'name' => 'ইসলামের ইতিহাস',
                'description' => 'ইসলামের গৌরবময় ইতিহাস ও ঐতিহ্য',
                'sort_order' => 5,
            ],
            [
                'name' => 'আকীদা ও বিশ্বাস',
                'description' => 'ইসলামী আকীদা ও বিশ্বাসের মূলনীতি',
                'sort_order' => 6,
            ],
            [
                'name' => 'দাওয়াহ ও তাবলীগ',
                'description' => 'ইসলামী দাওয়াহ ও প্রচারণা',
                'sort_order' => 7,
            ],
            [
                'name' => 'ইসলামী অর্থনীতি',
                'description' => 'ইসলামী অর্থনীতি ও ব্যাংকিং',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'is_active' => true,
            ]);
        }
    }
}