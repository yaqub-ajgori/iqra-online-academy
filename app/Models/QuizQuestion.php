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
        if ($this->type === 'multiple_choice') {
            return in_array($answer, $this->correct_answers);
        }
        
        if ($this->type === 'true_false') {
            return (bool) $answer === (bool) $this->correct_answers[0];
        }
        
        if ($this->type === 'short_answer') {
            // Case-insensitive comparison for short answers
            $correctAnswers = array_map('strtolower', $this->correct_answers);
            return in_array(strtolower($answer), $correctAnswers);
        }
        
        // For essay questions, manual grading is required
        if ($this->type === 'essay') {
            return false; // Requires manual grading
        }
        
        return false;
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