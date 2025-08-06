<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseEnrollmentFactory extends Factory
{
    public function definition(): array
    {
        $enrolledAt = $this->faker->dateTimeBetween('-6 months', 'now');
        $progressPercentage = $this->faker->numberBetween(0, 100);
        $isCompleted = $progressPercentage >= 100;
        
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'enrolled_at' => $enrolledAt,
            'enrollment_type' => $this->faker->randomElement(['paid', 'free', 'scholarship', 'trial']),
            'progress_percentage' => $progressPercentage,
            'lessons_completed' => $this->faker->numberBetween(0, 10),
            'is_completed' => $isCompleted,
            'is_active' => true,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress_percentage' => 100,
            'is_completed' => true,
            'lessons_completed' => $this->faker->numberBetween(5, 15),
        ]);
    }
}