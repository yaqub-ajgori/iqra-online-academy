<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        'price',
        'currency',
        'discount_price',
        'discount_expires_at',
        'duration_in_minutes',
        'status',
        'is_featured',
        'is_free',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
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
     * Get the course's primary instructor (backward compatibility).
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'instructor_id');
    }

    /**
     * Get the course's teacher (alias for instructor).
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'instructor_id');
    }

    /**
     * Get all instructors for this course (many-to-many).
     */
    public function instructors()
    {
        return $this->belongsToMany(Teacher::class, 'course_instructors')
                    ->withPivot(['role', 'bio', 'sort_order', 'is_active'])
                    ->wherePivot('is_active', true)
                    ->orderByPivot('sort_order')
                    ->withTimestamps();
    }

    /**
     * Get primary instructors only.
     */
    public function primaryInstructors()
    {
        return $this->instructors()->wherePivot('role', 'primary');
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
     * Get the course's reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    /**
     * Get only approved reviews.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(CourseReview::class)->approved();
    }

    /**
     * Get the course's modules.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order');
    }

    /**
     * Get all the lessons for the course.
     */
    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            CourseLesson::class,
            CourseModule::class,
            'course_id', // Foreign key on course_modules table
            'module_id', // Foreign key on course_lessons table
            'id',        // Local key on courses table
            'id'         // Local key on course_modules table
        )->orderBy('course_modules.sort_order')
         ->orderBy('course_lessons.sort_order');
    }

    /**
     * Get preview lessons for the course.
     */
    public function previewLessons(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->lessons()->where('course_lessons.is_preview', true);
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
     * Get the course's quizzes.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the course's certificates.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Scope to get only published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Check if course is published.
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get thumbnail attribute.
     */
    public function getThumbnailAttribute(): ?string
    {
        if (!$this->thumbnail_image) {
            return null;
        }

        // If the path already includes 'http', it's a full URL
        if (str_starts_with($this->thumbnail_image, 'http')) {
            return $this->thumbnail_image;
        }

        // Otherwise, build the full URL from the public storage path
        return asset('storage/' . $this->thumbnail_image);
    }

    /**
     * Get total lessons count for the course.
     */
    public function getTotalLessonsCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Get average rating for the course.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) $this->approvedReviews()->avg('rating') ?: 0;
    }

    /**
     * Get total reviews count.
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get rating distribution.
     */
    public function getRatingDistributionAttribute(): array
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $this->approvedReviews()->where('rating', $i)->count();
        }
        return $distribution;
    }

    /**
     * Get duration attribute, formatted from minutes.
     */
    public function getDurationAttribute(): string
    {
        $minutes = $this->duration_in_minutes;

        if (!$minutes || $minutes <= 0) {
            return 'স্ব-নির্ধারিত';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' ঘন্টা';
        }
        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ' মিনিট';
        }

        return implode(' ', $parts);
    }

    /**
     * Get enrollments count.
     */
    public function getEnrollmentsCountAttribute(): int
    {
        return $this->enrollments()->count();
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
