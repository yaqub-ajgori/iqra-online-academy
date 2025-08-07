<?php

namespace Database\Factories;

use App\Models\CourseLesson;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseLesson>
 */
class CourseLessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lessonTitles = [
            'ভূমিকা ও পরিচিতি',
            'মূল বিষয়ের ব্যাখ্যা',
            'বিস্তারিত আলোচনা',
            'উদাহরণ ও প্রয়োগ',
            'অনুশীলন ও পরীক্ষা',
            'সারসংক্ষেপ ও পুনরালোচনা',
            'প্রাথমিক ধারণা',
            'মধ্যম পর্যায়ের শিক্ষা',
            'উন্নত বিষয়াবলী',
            'সমাপনী আলোচনা'
        ];

        $lessonTypes = ['video', 'text', 'quiz', 'pdf'];

        return [
            'module_id' => CourseModule::factory(),
            'course_id' => null, // Will be set by the seeder
            'title' => fake()->randomElement($lessonTitles),
            'description' => fake()->paragraph(2),
            'content' => fake()->paragraph(4),
            'lesson_type' => fake()->randomElement($lessonTypes),
            'video_url' => fake()->url(),
            'video_duration' => fake()->numberBetween(300, 3600), // 5-60 minutes in seconds
            'sort_order' => fake()->numberBetween(1, 15),
            'is_preview' => fake()->boolean(20),
            'is_active' => true,
        ];
    }

    /**
     * Create a preview lesson.
     */
    public function preview(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_preview' => true,
        ]);
    }
}