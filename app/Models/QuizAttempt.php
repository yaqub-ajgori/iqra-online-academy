<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'answers',
        'score',
        'correct_answers',
        'total_questions',
        'started_at',
        'completed_at',
        'time_taken_seconds',
        'is_passed',
        'feedback',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'decimal:2',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the quiz that owns the attempt.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the student that made the attempt.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope: Completed attempts only.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope: Passed attempts only.
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Get formatted time taken.
     */
    public function getFormattedTimeTakenAttribute(): string
    {
        if (!$this->time_taken_seconds) {
            return '0:00';
        }

        $hours = floor($this->time_taken_seconds / 3600);
        $minutes = floor(($this->time_taken_seconds % 3600) / 60);
        $seconds = $this->time_taken_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get percentage score.
     */
    public function getPercentageScoreAttribute(): float
    {
        return $this->score ?? 0.0;
    }

    /**
     * Get grade letter.
     */
    public function getGradeAttribute(): string
    {
        $score = $this->score ?? 0;
        
        if ($score >= 90) return 'A+';
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'A-';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'B-';
        if ($score >= 60) return 'C+';
        if ($score >= 55) return 'C';
        if ($score >= 50) return 'C-';
        
        return 'F';
    }

    /**
     * Calculate and update the score for this attempt.
     */
    public function calculateScore(): void
    {
        $quiz = $this->quiz;
        $questions = $quiz->questions()->active()->get();
        
        $correctCount = 0;
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $studentAnswer = $this->answers[$question->id] ?? null;
            
            if ($question->isCorrectAnswer($studentAnswer)) {
                $correctCount++;
                $earnedPoints += $question->points;
            }
        }

        $this->update([
            'correct_answers' => $correctCount,
            'total_questions' => $questions->count(),
            'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0,
            'is_passed' => $totalPoints > 0 ? (($earnedPoints / $totalPoints) * 100) >= $quiz->passing_score : false,
        ]);
    }
}