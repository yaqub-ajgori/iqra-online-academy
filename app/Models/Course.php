<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'full_description',
        'category_id',
        'level',
        'instructor_id',
        'thumbnail_image',
        'preview_video_url',
        'price',
        'currency',
        'discount_price',
        'discount_expires_at',
        'duration_hours',
        'total_lessons',
        'total_quizzes',
        'total_assignments',
        'max_students',
        'current_students',
        'enrollment_starts_at',
        'enrollment_ends_at',
        'status',
        'is_featured',
        'is_free',
        'average_rating',
        'total_reviews',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_expires_at' => 'datetime',
        'enrollment_starts_at' => 'datetime',
        'enrollment_ends_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
        'average_rating' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    /**
     * Get the course's category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    /**
     * Get the course's instructor.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'instructor_id');
    }

    /**
     * Get the course's enrollments.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get the course's payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the course's modules.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order');
    }

    /**
     * Get the course's lessons.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }

    /**
     * Get the enrolled students.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_enrollments')
                    ->withPivot('enrolled_at', 'progress_percentage', 'is_completed')
                    ->withTimestamps();
    }

    /**
     * Scope to get only published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get only featured courses.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get only free courses.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Check if course enrollment is open.
     */
    public function isEnrollmentOpen(): bool
    {
        $now = now();
        
        if ($this->enrollment_starts_at && $now->isBefore($this->enrollment_starts_at)) {
            return false;
        }
        
        if ($this->enrollment_ends_at && $now->isAfter($this->enrollment_ends_at)) {
            return false;
        }
        
        if ($this->max_students && $this->current_students >= $this->max_students) {
            return false;
        }
        
        return $this->status === 'published';
    }

    /**
     * Get the effective price (considering discounts).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_free) {
            return 0.00;
        }
        
        if ($this->discount_price && 
            $this->discount_expires_at && 
            $this->discount_expires_at->isFuture()) {
            return $this->discount_price;
        }
        
        return $this->price;
    }
}
