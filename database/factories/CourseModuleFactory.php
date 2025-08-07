<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseModule>
 */
class CourseModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $moduleNames = [
            'পরিচিতি ও মূল বিষয়',
            'মৌলিক ধারণা',
            'বিস্তারিত আলোচনা',
            'প্রয়োগিক দিক',
            'উন্নত বিষয়াবলী',
            'সমাপনী ও মূল্যায়ন',
            'প্রাথমিক জ্ঞান',
            'মধ্যম পর্যায়ের শিক্ষা',
            'গভীর অধ্যয়ন',
            'বাস্তবিক প্রয়োগ'
        ];

        return [
            'course_id' => Course::factory(),
            'title' => fake()->randomElement($moduleNames),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}