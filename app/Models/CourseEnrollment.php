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
     * Get the certificate for this enrollment.
     */
    public function certificate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Certificate::class, 'enrollment_id');
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
        
        $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        
        $this->update([
            'progress_percentage' => $progressPercentage,
            'lessons_completed' => $completedLessons,
        ]);

        // Check if course is completed - must be exactly 100% and all lessons completed
        if ($completedLessons === $totalLessons && $totalLessons > 0 && $progressPercentage >= 100) {
            $this->markAsCompleted();
        } else {
            // Reset completion status if not actually completed
            if ($this->is_completed && $completedLessons < $totalLessons) {
                $this->update(['is_completed' => false]);
            }
        }
    }

    /**
     * Mark enrollment as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'progress_percentage' => 100,
        ]);
        
        // Create certificate if it doesn't exist
        if (!$this->certificate) {
            Certificate::createForEnrollment($this);
        }
    }

    /**
     * Generate consistent certificate data for this enrollment.
     */
    public function getCertificateData(): array
    {
        if (!$this->is_completed || $this->payment_status !== 'completed') {
            return [];
        }

        // If certificate record exists, use it
        if ($this->certificate) {
            return [
                'certificate_number' => $this->certificate->certificate_number,
                'verification_code' => $this->certificate->verification_code,
                'student_name' => $this->certificate->student_name,
                'course_title' => $this->certificate->course_title,
                'course_description' => $this->certificate->course_description,
                'instructor' => $this->certificate->instructor_name,
                'completion_date' => $this->certificate->completed_at,
                'issue_date' => $this->certificate->issued_at,
                'enrollment_id' => $this->id,
                'course_slug' => $this->course->slug,
                'is_valid' => $this->certificate->is_valid,
                'status' => $this->certificate->display_status,
            ];
        }

        // Fallback to generate data (for backward compatibility)
        return [
            'certificate_number' => $this->getCertificateNumber(),
            'verification_code' => $this->getVerificationCode(),
            'student_name' => $this->student->full_name ?? $this->student->user->name,
            'course_title' => $this->course->title,
            'course_description' => $this->course->description ?? $this->course->full_description,
            'instructor' => $this->course->instructor->full_name ?? $this->course->teacher->full_name ?? 'Iqra Online Academy',
            'completion_date' => $this->updated_at,
            'issue_date' => $this->updated_at,
            'enrollment_id' => $this->id,
            'course_slug' => $this->course->slug,
            'is_valid' => true,
            'status' => 'Valid',
        ];
    }

    /**
     * Generate consistent certificate number.
     */
    public function getCertificateNumber(): string
    {
        return 'IOA-' . $this->updated_at->format('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate consistent verification code based on enrollment data.
     */
    public function getVerificationCode(): string
    {
        // Generate a consistent verification code based on enrollment data
        // This ensures the same code is generated every time for the same enrollment
        $data = $this->id . '-' . $this->student_id . '-' . $this->course_id . '-' . $this->updated_at->format('Y-m-d');
        return 'CERT' . strtoupper(substr(md5($data), 0, 8));
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
