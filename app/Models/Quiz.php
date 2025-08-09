<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model    
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'course_id',
        'lesson_id',
        'passing_score',
        'time_limit_minutes',
        'max_attempts',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'passing_score' => 'integer',
        'time_limit_minutes' => 'integer',
        'max_attempts' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the course this quiz belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson this quiz belongs to.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    /**
     * Get all questions for this quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    /**
     * Get all attempts for this quiz.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get attempts for a specific user.
     */
    public function attemptsFor($userId): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->where('user_id', $userId);
    }

    /**
     * Scope to get only active quizzes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the total number of questions.
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Get the total points for this quiz.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    /**
     * Check if a user can take this quiz.
     */
    public function canUserTakeQuiz($userId): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $attempts = $this->attemptsFor($userId)->count();
        return $attempts < $this->max_attempts;
    }

    /**
     * Get the best score for a user.
     */
    public function getBestScoreForUser($userId): ?int
    {
        return $this->attemptsFor($userId)->max('score');
    }
}
