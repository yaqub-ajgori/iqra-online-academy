<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_at',
        'enrollment_type',
        'payment_id',
        'amount_paid',
        'currency',
        'payment_status',
        'progress_percentage',
        'lessons_completed',
        'is_active',
        'is_completed',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the student that owns this enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course for this enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the payment for this enrollment.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the lesson progress for this enrollment.
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id');
    }

    /**
     * Scope to get only active enrollments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only completed enrollments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope to get enrollments by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Check if enrollment has access.
     */
    public function hasAccess(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->payment_status !== 'completed' && $this->enrollment_type === 'paid') {
            return false;
        }

        if ($this->access_expires_at && $this->access_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Update progress based on lesson progress.
     */
    public function updateProgress(): void
    {
        $totalLessons = $this->course->lessons()->count();
        $completedLessons = $this->lessonProgress()->where('is_completed', true)->count();
        
        $this->update([
            'progress_percentage' => $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0,
            'lessons_completed' => $completedLessons,
            'last_accessed_at' => now(),
        ]);

        // Check if course is completed
        if ($completedLessons === $totalLessons && $totalLessons > 0) {
            $this->markAsCompleted();
        }
    }

    /**
     * Mark enrollment as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($enrollment) {
            if ($enrollment->payment_id) {
                $payment = \App\Models\Payment::find($enrollment->payment_id);
                if ($payment) {
                    $enrollment->amount_paid = $payment->amount;
                }
            }
        });
    }
}
