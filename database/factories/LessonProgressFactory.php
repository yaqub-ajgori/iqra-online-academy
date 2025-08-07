<?php

namespace Database\Factories;

use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonProgress>
 */
class LessonProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enrollment_id' => CourseEnrollment::factory(),
            'lesson_id' => CourseLesson::factory(),
            'started_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'completed_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-2 months', 'now') : null,
            'time_spent_seconds' => fake()->numberBetween(300, 7200), // 5 minutes to 2 hours
            'completion_percentage' => fake()->randomFloat(2, 0, 100),
            'is_completed' => fake()->boolean(70),
            'notes' => fake()->boolean(30) ? fake()->sentence(10) : null,
        ];
    }

    /**
     * Create a completed lesson progress.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'completed_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}