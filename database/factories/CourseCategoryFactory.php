<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseCategory>
 */
class CourseCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'কুরআন শিক্ষা',
            'হাদিস শরীফ',
            'ইসলামিক ফিকহ',
            'আরবি ভাষা',
            'ইসলামের ইতিহাস',
            'তাফসীর',
            'আকিদা ও বিশ্বাস',
            'ইসলামি আইন',
            'দুআ ও যিকির',
            'ইসলামি শিষ্টাচার'
        ];

        $name = fake()->unique()->randomElement($categories);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }
}