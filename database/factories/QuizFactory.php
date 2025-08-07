<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quizTitles = [
            'মৌলিক জ্ঞান যাচাই',
            'পূর্ণাঙ্গ মূল্যায়ন',
            'চূড়ান্ত পরীক্ষা',
            'প্রাথমিক যাচাই',
            'মধ্যবর্তী মূল্যায়ন',
            'সমাপনী পরীক্ষা'
        ];

        $title = fake()->randomElement($quizTitles);

        return [
            'course_id' => Course::factory(),
            'lesson_id' => null,
            'title' => $title,
            'slug' => Str::slug($title . '-' . uniqid()),
            'description' => fake()->paragraph(2),
            'type' => fake()->randomElement(['quiz', 'exam', 'assessment']),
            'time_limit_minutes' => fake()->randomElement([30, 45, 60, 90, 120, null]),
            'max_attempts' => fake()->randomElement([1, 2, 3, 5]),
            'passing_score' => fake()->randomElement([50, 60, 70, 80]),
            'randomize_questions' => fake()->boolean(30),
            'show_results_immediately' => true,
            'allow_review' => true,
            'available_from' => fake()->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'available_until' => fake()->optional(0.3)->dateTimeBetween('now', '+6 months'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Indicate that the quiz is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the quiz is for a specific lesson.
     */
    public function forLesson(CourseLesson $lesson): static
    {
        return $this->state(fn (array $attributes) => [
            'course_id' => $lesson->module->course_id,
            'lesson_id' => $lesson->id,
        ]);
    }

    /**
     * Indicate that the quiz is an exam.
     */
    public function exam(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'exam',
            'time_limit_minutes' => fake()->numberBetween(60, 180),
            'max_attempts' => 1,
            'passing_score' => 70.00,
        ]);
    }

    /**
     * Indicate that the quiz has no time limit.
     */
    public function noTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_limit_minutes' => null,
        ]);
    }
}