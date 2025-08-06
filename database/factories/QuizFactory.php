<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseLesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        $quizTitles = [
            'প্রাথমিক পর্যায়ের পরীক্ষা',
            'মধ্যবর্তী মূল্যায়ন',
            'চূড়ান্ত পরীক্ষা',
            'অধ্যায় ভিত্তিক কুইজ',
            'দক্ষতা যাচাই পরীক্ষা',
            'সাপ্তাহিক মূল্যায়ন',
            'মাসিক পরীক্ষা',
            'বার্ষিক পরীক্ষা'
        ];

        return [
            'course_id' => Course::factory(),
            'lesson_id' => null, // Optional lesson association
            'title' => $this->faker->randomElement($quizTitles),
            'description' => $this->faker->paragraph(2),
            'type' => $this->faker->randomElement(['quiz', 'exam', 'assessment']),
            'time_limit_minutes' => $this->faker->optional()->randomElement([15, 30, 45, 60, 90, 120]),
            'max_attempts' => $this->faker->numberBetween(1, 5),
            'passing_score' => $this->faker->randomElement([60.00, 70.00, 75.00, 80.00]),
            'randomize_questions' => $this->faker->boolean(50),
            'show_results_immediately' => $this->faker->boolean(70),
            'allow_review' => $this->faker->boolean(80),
            'available_from' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'available_until' => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function exam(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'exam',
            'time_limit_minutes' => 120,
            'max_attempts' => 2,
            'passing_score' => 75.00,
            'show_results_immediately' => false,
        ]);
    }

    public function quickQuiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz',
            'time_limit_minutes' => 15,
            'max_attempts' => 3,
            'passing_score' => 60.00,
        ]);
    }
}