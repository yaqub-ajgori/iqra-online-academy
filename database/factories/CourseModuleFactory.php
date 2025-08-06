<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseModuleFactory extends Factory
{
    public function definition(): array
    {
        $modules = [
            'ভূমিকা ও প্রাথমিক ধারণা',
            'মৌলিক বিষয়সমূহ',
            'বিস্তারিত আলোচনা',
            'প্রয়োগিক দিকসমূহ',
            'উদাহরণ ও ব্যাখ্যা',
            'বাস্তব প্রয়োগ',
            'পরীক্ষা ও মূল্যায়ন',
            'সমাপনী ও পর্যালোচনা'
        ];

        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->randomElement($modules),
            'description' => $this->faker->paragraph(2),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}