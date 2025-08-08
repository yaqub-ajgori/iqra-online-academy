<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'answers',
        'score',
        'correct_answers',
        'total_questions',
        'is_passed',
        'started_at',
        'completed_at',
        'time_spent_seconds',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'correct_answers' => 'integer',
        'total_questions' => 'integer',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent_seconds' => 'integer',
    ];

    /**
     * Get the quiz this attempt belongs to.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the user who made this attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted time spent.
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        if (!$this->time_spent_seconds) {
            return 'N/A';
        }

        $minutes = floor($this->time_spent_seconds / 60);
        $seconds = $this->time_spent_seconds % 60;

        if ($minutes > 0) {
            return "{$minutes} মিনিট {$seconds} সেকেন্ড";
        }

        return "{$seconds} সেকেন্ড";
    }

    /**
     * Calculate and update the score based on answers.
     */
    public function calculateScore(): void
    {
        if (!$this->answers || !$this->quiz) {
            return;
        }

        $quiz = $this->quiz;
        $questions = $quiz->questions;
        $correctAnswers = 0;
        $totalQuestions = $questions->count();
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $userAnswer = $this->answers[$question->id] ?? null;

            if ($userAnswer !== null && $question->isAnswerCorrect($userAnswer)) {
                $correctAnswers++;
                $earnedPoints += $question->points;
            }
        }

        $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;

        $this->update([
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'score' => $scorePercentage,
            'is_passed' => $scorePercentage >= $quiz->passing_score,
        ]);
    }
}
