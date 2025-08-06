<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseReviewHelpfulVote;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Show course reviews
     */
    public function index(Course $course, Request $request)
    {
        $sortBy = $request->get('sort', 'recent'); // recent, helpful, rating
        $filterRating = $request->get('rating', null);

        $query = $course->approvedReviews()->with(['student', 'user']);

        // Apply rating filter
        if ($filterRating && in_array($filterRating, [1, 2, 3, 4, 5])) {
            $query->where('rating', $filterRating);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'helpful':
                $query->mostHelpful();
                break;
            case 'rating':
                $query->highRated();
                break;
            case 'recent':
            default:
                $query->recent();
                break;
        }

        $reviews = $query->paginate(10);

        // Check if current user can write a review
        $canWriteReview = false;
        $existingReview = null;
        
        if (Auth::check() && Auth::user()->student) {
            $enrollment = CourseEnrollment::where('student_id', Auth::user()->student->id)
                ->where('course_id', $course->id)
                ->where('is_active', true)
                ->first();

            $canWriteReview = (bool) $enrollment;

            $existingReview = CourseReview::where('student_id', Auth::user()->student->id)
                ->where('course_id', $course->id)
                ->first();
        }

        return Inertia::render('Frontend/Reviews/Index', [
            'course' => $course->load(['category', 'instructor']),
            'reviews' => $reviews,
            'canWriteReview' => $canWriteReview,
            'existingReview' => $existingReview,
            'currentSort' => $sortBy,
            'currentRating' => $filterRating,
            'ratingDistribution' => $course->rating_distribution,
        ]);
    }

    /**
     * Store a new review
     */
    public function store(Request $request, Course $course)
    {
        if (!Auth::check() || !Auth::user()->student) {
            throw ValidationException::withMessages([
                'auth' => 'You must be logged in as a student to write a review.',
            ]);
        }

        $student = Auth::user()->student;

        // Check if student is enrolled
        $enrollment = CourseEnrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('is_active', true)
            ->first();

        if (!$enrollment) {
            throw ValidationException::withMessages([
                'enrollment' => 'You must be enrolled in this course to write a review.',
            ]);
        }

        // Check if student already has a review
        $existingReview = CourseReview::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingReview) {
            throw ValidationException::withMessages([
                'duplicate' => 'You have already reviewed this course.',
            ]);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'review_text' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = CourseReview::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
            'status' => CourseReview::STATUS_PENDING,
            'metadata' => [
                'progress_at_review' => $enrollment->progress_percentage,
                'completed_course' => $enrollment->is_completed,
            ],
        ]);

        return redirect()->back()->with('success', 'আপনার রিভিউ সফলভাবে জমা দেওয়া হয়েছে। এটি অনুমোদনের জন্য পর্যালোচনা করা হবে।');
    }

    /**
     * Update an existing review (within 24 hours)
     */
    public function update(Request $request, Course $course, CourseReview $review)
    {
        if (!Auth::check() || Auth::id() !== $review->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$review->canBeEditedBy(Auth::user())) {
            throw ValidationException::withMessages([
                'edit' => 'You can only edit your review within 24 hours of submission and while it\'s pending approval.',
            ]);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'review_text' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update([
            'title' => $request->title,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'আপনার রিভিউ সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Mark a review as helpful
     */
    public function markHelpful(Request $request, CourseReview $review)
    {
        $userId = Auth::id();
        $ipAddress = $request->ip();
        $sessionId = session()->getId();

        // Check if already voted
        if (CourseReviewHelpfulVote::hasVoted($review->id, $userId, $ipAddress, $sessionId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked this review as helpful.',
            ], 422);
        }

        $voted = CourseReviewHelpfulVote::createVote($review->id, $userId, $ipAddress, $sessionId);

        if ($voted) {
            return response()->json([
                'success' => true,
                'helpful_count' => $review->fresh()->helpful_count,
                'message' => 'Thank you for your feedback!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to record your vote.',
        ], 422);
    }

    /**
     * Report a review
     */
    public function report(Request $request, CourseReview $review)
    {
        $request->validate([
            'reason' => 'required|string|in:spam,inappropriate,fake,other',
            'details' => 'nullable|string|max:500',
        ]);

        // Increment report count
        $review->increment('reported_count');

        // Log the report (you could create a separate table for this)
        \Log::info('Review reported', [
            'review_id' => $review->id,
            'reported_by' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
            'ip_address' => $request->ip(),
        ]);

        // Auto-hide if too many reports
        if ($review->reported_count >= 5) {
            $review->update(['status' => CourseReview::STATUS_HIDDEN]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Thank you for helping maintain our community standards.',
        ]);
    }

    /**
     * Get reviews for homepage testimonials
     */
    public function getFeaturedReviews()
    {
        $reviews = CourseReview::featured()
            ->with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->student->name,
                    'course' => $review->course->title,
                    'review' => $review->review_text,
                    'rating' => $review->rating,
                    'date' => $review->bengali_date,
                    'verified' => $review->is_verified_purchase,
                ];
            });

        return response()->json($reviews);
    }
}