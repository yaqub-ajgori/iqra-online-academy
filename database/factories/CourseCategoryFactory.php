<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseCategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'কুরআন শিক্ষা',
            'হাদিস শরীফ',
            'ফিকহ ও আইন',
            'আরবি ভাষা',
            'ইসলামের ইতিহাস',
            'আকীদা ও বিশ্বাস',
            'দাওয়াহ ও তাবলীগ',
            'ইসলামী অর্থনীতি'
        ];

        $categoryName = $this->faker->randomElement($categories);
        
        return [
            'name' => $categoryName,
            'slug' => Str::slug($categoryName) . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'is_active' => true,
        ];
    }
}