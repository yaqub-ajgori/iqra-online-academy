<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = now()->subDays(fake()->numberBetween(1, 60));
        $completedAt = fake()->boolean(80) ? 
            $startedAt->copy()->addMinutes(fake()->numberBetween(5, 120)) : null;
        $timeTaken = $completedAt ? $startedAt->diffInSeconds($completedAt) : null;
        
        $totalQuestions = fake()->numberBetween(10, 30);
        $correctAnswers = fake()->numberBetween(0, $totalQuestions);
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        return [
            'quiz_id' => Quiz::factory(),
            'student_id' => Student::factory(),
            'answers' => $this->generateAnswers($totalQuestions),
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'score' => $completedAt ? round($score, 2) : null,
            'total_questions' => $totalQuestions,
            'correct_answers' => $completedAt ? $correctAnswers : 0,
            'time_taken_seconds' => $timeTaken,
            'is_passed' => $completedAt ? ($score >= 70) : false,
            'feedback' => $completedAt ? fake()->optional(0.3)->sentence(10) : null,
        ];
    }

    /**
     * Generate random answers for the attempt.
     */
    private function generateAnswers(int $totalQuestions): array
    {
        $answers = [];
        
        for ($i = 1; $i <= $totalQuestions; $i++) {
            $questionId = fake()->numberBetween(1, 100);
            $answers[$questionId] = fake()->randomElement([0, 1, 2, 3, 'সত্য', 'মিথ্যা', fake()->word()]);
        }
        
        return $answers;
    }

    /**
     * Create a completed attempt.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $totalQuestions = fake()->numberBetween(10, 20);
            $correctAnswers = fake()->numberBetween(ceil($totalQuestions * 0.7), $totalQuestions);
            $score = ($correctAnswers / $totalQuestions) * 100;
            
            return [
                'completed_at' => now()->subDays(fake()->numberBetween(1, 30)),
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'score' => round($score, 2),
                'is_passed' => true,
            ];
        });
    }

    /**
     * Create an incomplete attempt.
     */
    public function incomplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => null,
            'time_taken_seconds' => null,
            'score' => null,
            'correct_answers' => 0,
            'is_passed' => false,
        ]);
    }

    /**
     * Create a failed attempt.
     */
    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            $totalQuestions = fake()->numberBetween(10, 20);
            $correctAnswers = fake()->numberBetween(0, floor($totalQuestions * 0.6));
            $score = ($correctAnswers / $totalQuestions) * 100;
            
            return [
                'completed_at' => now()->subDays(fake()->numberBetween(1, 30)),
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'score' => round($score, 2),
                'is_passed' => false,
            ];
        });
    }
}