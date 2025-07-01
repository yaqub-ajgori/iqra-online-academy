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
        'price',
        'currency',
        'discount_price',
        'discount_expires_at',
        'duration_hours',
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
     * Get the course's instructor.
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
        return $this->thumbnail_image;
    }

    /**
     * Get duration attribute.
     */
    public function getDurationAttribute(): string
    {
        if ($this->duration_hours) {
            return $this->duration_hours . ' ঘন্টা';
        }
        return 'স্ব-নির্ধারিত';
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
