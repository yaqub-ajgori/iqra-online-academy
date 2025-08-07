<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'correct_answers',
        'explanation',
        'points',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'points' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Scope: Active questions only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if given answer is correct.
     */
    public function isCorrectAnswer($answer): bool
    {
        // Ensure correct_answers is always an array
        $correctAnswers = $this->getCorrectAnswersAsArray();
        
        if ($this->type === 'multiple_choice') {
            return in_array($answer, $correctAnswers);
        }
        
        if ($this->type === 'true_false') {
            // Direct string comparison for Bengali true/false values
            return $answer === $correctAnswers[0];
        }
        
        if ($this->type === 'short_answer') {
            // Case-insensitive comparison for short answers
            $correctAnswers = array_map('strtolower', $correctAnswers);
            return in_array(strtolower((string)$answer), $correctAnswers);
        }
        
        // For essay questions, manual grading is required
        if ($this->type === 'essay') {
            return false; // Requires manual grading
        }
        
        return false;
    }

    /**
     * Get correct answers as array, handling string data.
     */
    private function getCorrectAnswersAsArray(): array
    {
        $correctAnswers = $this->correct_answers;
        
        // Handle case where correct_answers might be a string
        if (is_string($correctAnswers)) {
            // Try to decode as JSON first
            $decoded = json_decode($correctAnswers, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            // If not JSON, treat as single answer
            return [$correctAnswers];
        }
        
        // If already array, return as is
        if (is_array($correctAnswers)) {
            return $correctAnswers;
        }
        
        // Fallback to empty array
        return [];
    }

    /**
     * Get formatted question options.
     */
    public function getFormattedOptionsAttribute(): array
    {
        if ($this->type !== 'multiple_choice' || !$this->options) {
            return [];
        }

        return collect($this->options)->map(function ($option, $key) {
            return [
                'key' => is_numeric($key) ? chr(65 + $key) : $key, // A, B, C, D...
                'value' => $option,
                'index' => $key
            ];
        })->toArray();
    }
}