<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseCategory;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Programming',
            'Mathematics',
            'Science',
            'Languages',
            'Business',
        ];

        foreach ($categories as $cat) {
            CourseCategory::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'is_active' => true,
            ]);
        }
    }
}
