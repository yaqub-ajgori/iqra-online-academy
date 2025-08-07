<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'slug',
        'description',
        'type',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'randomize_questions',
        'show_results_immediately',
        'allow_review',
        'available_from',
        'available_until',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'passing_score' => 'decimal:2',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'is_active' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    /**
     * Get the course that owns the quiz.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson that owns the quiz (optional).
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    /**
     * Get the quiz questions.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    /**
     * Get the quiz attempts.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Scope: Active quizzes only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
     * Scope: Available quizzes (within time range).
     */
    public function scopeAvailable($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('available_from')
              ->orWhere('available_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('available_until')
              ->orWhere('available_until', '>=', $now);
        });
    }

    /**
     * Check if quiz is available now.
     */
    public function getIsAvailableAttribute(): bool
    {
        $now = now();
        
        if ($this->available_from && $now->lessThan($this->available_from)) {
            return false;
        }
        
        if ($this->available_until && $now->greaterThan($this->available_until)) {
            return false;
        }
        
        return $this->is_active;
    }

    /**
     * Get total questions count.
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Get total points available.
     */
    public function getTotalPointsAttribute(): float
    {
        return $this->questions()->sum('points');
    }
}