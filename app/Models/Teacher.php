<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'highest_qualification',
        'islamic_qualifications',
        'secular_qualifications',
        'specializations',
        'teaching_experience_years',
        'islamic_teaching_experience_years',
        'previous_institutions',
        'bio',
        'profile_picture',
        'languages_spoken',
        'preferred_subjects',
        'availability_schedule',
        'hourly_rate',
        'is_verified',
        'verification_documents',
        'verification_status',
        'verified_at',
        'verified_by',
        'average_rating',
        'total_students_taught',
        'total_courses_taught',
        'is_active',
        'can_create_courses',
    ];

    protected $casts = [
        'islamic_qualifications' => 'array',
        'secular_qualifications' => 'array',
        'specializations' => 'array',
        'previous_institutions' => 'array',
        'languages_spoken' => 'array',
        'preferred_subjects' => 'array',
        'availability_schedule' => 'array',
        'verification_documents' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'average_rating' => 'decimal:2',
        'is_active' => 'boolean',
        'can_create_courses' => 'boolean',
    ];

    /**
     * Get the user that owns this teacher profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses taught by this teacher.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Scope to get only verified teachers.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to get only active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
