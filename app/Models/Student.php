<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'district',
        'country',
        'postal_code',
        'islamic_knowledge_level',
        'previous_islamic_education',
        'memorization_status',
        'arabic_proficiency',
        'preferred_language',
        'learning_goals',
        'study_schedule_preference',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'profile_picture',
        'bio',
        'occupation',
        'education_level',
        'is_active',
        'is_verified',
        'verification_documents',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'verification_documents' => 'array',
    ];

    /**
     * Get the user that owns this student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student's course enrollments.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get the student's payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the student's enrolled courses.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->withPivot('enrolled_at', 'progress_percentage', 'is_completed')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active students.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only verified students.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Get the student's age.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }
}
