<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAttemptFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-1 week', 'now');
        $isCompleted = $this->faker->boolean(80);
        $completedAt = $isCompleted ? $this->faker->dateTimeBetween($startedAt, 'now') : null;
        
        $totalQuestions = $this->faker->numberBetween(5, 20);
        $correctAnswers = $isCompleted ? $this->faker->numberBetween(0, $totalQuestions) : 0;
        $score = $isCompleted ? ($correctAnswers / $totalQuestions) * 100 : null;
        
        // Generate sample answers
        $answers = [];
        for ($i = 1; $i <= $totalQuestions; $i++) {
            $answers[$i] = $this->faker->randomElement(['A', 'B', 'C', 'D', 'True', 'False']);
        }

        return [
            'quiz_id' => Quiz::factory(),
            'student_id' => Student::factory(),
            'answers' => $answers,
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'time_taken_seconds' => $completedAt ? $this->faker->numberBetween(300, 3600) : null,
            'is_passed' => $isCompleted && $score >= 70,
            'feedback' => $isCompleted ? $this->faker->optional()->sentence() : null,
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $startedAt = $this->faker->dateTimeBetween('-1 week', 'now');
            $completedAt = $this->faker->dateTimeBetween($startedAt, 'now');
            $totalQuestions = $attributes['total_questions'] ?? 10;
            $correctAnswers = $this->faker->numberBetween(0, $totalQuestions);
            $score = ($correctAnswers / $totalQuestions) * 100;
            
            return [
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'score' => $score,
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'time_taken_seconds' => $this->faker->numberBetween(300, 3600),
                'is_passed' => $score >= 70,
                'feedback' => $this->faker->optional()->sentence(),
            ];
        });
    }

    public function passed(): static
    {
        return $this->state(function (array $attributes) {
            $totalQuestions = $attributes['total_questions'] ?? 10;
            $correctAnswers = $this->faker->numberBetween(ceil($totalQuestions * 0.7), $totalQuestions);
            $score = ($correctAnswers / $totalQuestions) * 100;
            
            return [
                'completed_at' => now(),
                'score' => $score,
                'correct_answers' => $correctAnswers,
                'is_passed' => true,
            ];
        });
    }

    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            $totalQuestions = $attributes['total_questions'] ?? 10;
            $correctAnswers = $this->faker->numberBetween(0, floor($totalQuestions * 0.6));
            $score = ($correctAnswers / $totalQuestions) * 100;
            
            return [
                'completed_at' => now(),
                'score' => $score,
                'correct_answers' => $correctAnswers,
                'is_passed' => false,
            ];
        });
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => now()->subMinutes(10),
            'completed_at' => null,
            'score' => null,
            'time_taken_seconds' => null,
            'is_passed' => false,
        ]);
    }
}