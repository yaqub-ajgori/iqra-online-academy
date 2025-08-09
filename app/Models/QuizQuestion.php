<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the quiz this question belongs to.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Check if the given answer is correct.
     */
    public function isAnswerCorrect($answer): bool
    {
        switch ($this->type) {
            case 'multiple_choice':
                return (int) $answer === (int) $this->correct_answer;
            case 'true_false':
                return $answer === $this->correct_answer;
            case 'short_answer':
                return strtolower(trim($answer)) === strtolower(trim($this->correct_answer));
            default:
                return false;
        }
    }

    /**
     * Get formatted options for multiple choice questions.
     */
    public function getFormattedOptionsAttribute(): ?array
    {
        if ($this->type !== 'multiple_choice' || !$this->options) {
            return null;
        }

        return array_map(function ($option, $index) {
            return [
                'index' => $index,
                'letter' => chr(65 + $index), // A, B, C, D...
                'text' => $option,
            ];
        }, $this->options, array_keys($this->options));
    }
}
