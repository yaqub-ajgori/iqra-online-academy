<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class CourseReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'user_id',
        'review_text',
        'rating',
        'title',
        'status',
        'approved_at',
        'approved_by',
        'moderation_notes',
        'is_featured',
        'is_verified_purchase',
        'helpful_count',
        'reported_count',
        'metadata',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'helpful_count' => 'integer',
        'reported_count' => 'integer',
        'metadata' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_HIDDEN = 'hidden';

    /**
     * Get the course this review belongs to
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the student who wrote this review
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who wrote this review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved this review
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get helpful votes for this review
     */
    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(CourseReviewHelpfulVote::class, 'review_id');
    }

    /**
     * Scope: Only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Only featured reviews (for homepage testimonials)
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->approved();
    }

    /**
     * Scope: Only verified purchase reviews
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Scope: Order by rating (highest first)
     */
    public function scopeHighRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Scope: Order by helpful count
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Scope: Recent reviews first
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Check if this review can be edited by the given user
     */
    public function canBeEditedBy(User $user): bool
    {
        // Student can edit their own pending review within 24 hours
        return $this->user_id === $user->id 
            && $this->status === self::STATUS_PENDING 
            && $this->created_at->diffInHours(now()) < 24;
    }

    /**
     * Approve this review
     */
    public function approve(User $approver): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $approver->id,
        ]);
    }

    /**
     * Reject this review
     */
    public function reject(User $rejector, string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $rejector->id,
            'moderation_notes' => $reason,
        ]);
    }

    /**
     * Mark as featured (for homepage testimonials)
     */
    public function markAsFeatured(): void
    {
        $this->update(['is_featured' => true]);
    }

    /**
     * Remove from featured
     */
    public function unmarkAsFeatured(): void
    {
        $this->update(['is_featured' => false]);
    }

    /**
     * Get formatted rating for display
     */
    public function getFormattedRatingAttribute(): string
    {
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get Bengali formatted date
     */
    public function getBengaliDateAttribute(): string
    {
        $months = [
            1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
            5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
        ];

        $day = $this->created_at->day;
        $month = $months[$this->created_at->month];
        $year = $this->created_at->year;

        return "{$day} {$month}, {$year}";
    }

    /**
     * Auto-detect if this is a verified purchase
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            // Check if student is enrolled in the course
            $isEnrolled = CourseEnrollment::where('student_id', $review->student_id)
                ->where('course_id', $review->course_id)
                ->where('is_active', true)
                ->exists();

            $review->is_verified_purchase = $isEnrolled;
        });
    }
}