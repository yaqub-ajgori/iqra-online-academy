<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'verification_code',
        'enrollment_id',
        'student_id',
        'course_id',
        'student_name',
        'course_title',
        'course_description',
        'instructor_name',
        'final_score',
        'total_lessons',
        'status',
        'issued_at',
        'completed_at',
        'expires_at',
        'revocation_reason',
        'revoked_at',
        'revoked_by',
        'issued_by',
        'metadata',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'final_score' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the enrollment that owns this certificate.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    /**
     * Get the student that owns this certificate.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course for this certificate.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who issued this certificate.
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who revoked this certificate.
     */
    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Scope to get only issued certificates.
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope to get only revoked certificates.
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    /**
     * Scope to get only expired certificates.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function ($query) {
                        $query->whereNotNull('expires_at')
                              ->where('expires_at', '<', now());
                    });
    }

    /**
     * Check if certificate is valid.
     */
    public function getIsValidAttribute(): bool
    {
        return $this->status === 'issued' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Get the certificate's display status.
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'revoked') {
            return 'Revoked';
        }
        
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Expired';
        }
        
        return 'Valid';
    }

    /**
     * Revoke this certificate.
     */
    public function revoke(string $reason, ?int $revokedBy = null): void
    {
        $this->update([
            'status' => 'revoked',
            'revocation_reason' => $reason,
            'revoked_at' => now(),
            'revoked_by' => $revokedBy,
        ]);
    }

    /**
     * Generate a new certificate for an enrollment.
     */
    public static function createForEnrollment(CourseEnrollment $enrollment, ?int $issuedBy = null): self
    {
        // Load necessary relationships
        $enrollment->load(['student.user', 'course', 'course.instructor', 'course.teacher']);
        
        $certificateNumber = 'IOA-' . $enrollment->updated_at->format('Y') . '-' . str_pad($enrollment->id, 4, '0', STR_PAD_LEFT);
        $verificationCode = 'CERT' . strtoupper(substr(md5($enrollment->id . '-' . $enrollment->student_id . '-' . $enrollment->course_id . '-' . $enrollment->updated_at->format('Y-m-d')), 0, 8));

        return self::create([
            'certificate_number' => $certificateNumber,
            'verification_code' => $verificationCode,
            'enrollment_id' => $enrollment->id,
            'student_id' => $enrollment->student_id,
            'course_id' => $enrollment->course_id,
            'student_name' => $enrollment->student->full_name ?? $enrollment->student->user->name,
            'course_title' => $enrollment->course->title,
            'course_description' => $enrollment->course->description ?? $enrollment->course->full_description,
            'instructor_name' => $enrollment->course->instructor->full_name ?? $enrollment->course->teacher->full_name ?? 'Iqra Online Academy',
            'final_score' => $enrollment->progress_percentage,
            'total_lessons' => $enrollment->lessons_completed,
            'status' => 'issued',
            'issued_at' => now(),
            'completed_at' => $enrollment->updated_at,
            'issued_by' => $issuedBy,
        ]);
    }
}