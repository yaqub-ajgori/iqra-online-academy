<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'is_completed',
        'completion_percentage',
        'time_spent_seconds',
        'started_at',
        'completed_at',
        'last_accessed_at',
        'notes',
        'bookmarks',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completion_percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'bookmarks' => 'array',
    ];

    /**
     * Get the enrollment that owns this progress.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }

    /**
     * Get the lesson that this progress belongs to.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    /**
     * Get the student through the enrollment.
     */
    public function student()
    {
        return $this->enrollment->student;
    }
}
