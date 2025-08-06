<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseReviewHelpfulVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'ip_address',
        'session_id',
    ];

    /**
     * Get the review this vote belongs to
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(CourseReview::class, 'review_id');
    }

    /**
     * Get the user who voted (null for guest votes)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a user or guest has already voted on a review
     */
    public static function hasVoted(int $reviewId, ?int $userId = null, ?string $ipAddress = null, ?string $sessionId = null): bool
    {
        $query = static::where('review_id', $reviewId);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where(function ($q) use ($ipAddress, $sessionId) {
                if ($ipAddress) {
                    $q->where('ip_address', $ipAddress);
                }
                if ($sessionId) {
                    $q->orWhere('session_id', $sessionId);
                }
            });
        }

        return $query->exists();
    }

    /**
     * Create a helpful vote and update the review count
     */
    public static function createVote(int $reviewId, ?int $userId = null, ?string $ipAddress = null, ?string $sessionId = null): bool
    {
        // Check if already voted
        if (static::hasVoted($reviewId, $userId, $ipAddress, $sessionId)) {
            return false;
        }

        // Create the vote
        static::create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'session_id' => $sessionId,
        ]);

        // Update the helpful count on the review
        CourseReview::where('id', $reviewId)->increment('helpful_count');

        return true;
    }
}