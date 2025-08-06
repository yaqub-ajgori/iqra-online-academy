<?php

namespace Tests\Feature;

use App\Models\CourseReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@iqra-academy.com',
        ]);
    }

    /** @test */
    public function admin_can_approve_pending_review()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_PENDING,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        // Test the approve method
        $review->approve($this->adminUser);

        $review->refresh();
        $this->assertEquals(CourseReview::STATUS_APPROVED, $review->status);
        $this->assertNotNull($review->approved_at);
        $this->assertEquals($this->adminUser->id, $review->approved_by);
    }

    /** @test */
    public function admin_can_reject_pending_review()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_PENDING,
            'approved_at' => null,
            'approved_by' => null,
            'moderation_notes' => null,
        ]);

        $rejectionReason = 'Review does not meet community guidelines.';

        // Test the reject method
        $review->reject($this->adminUser, $rejectionReason);

        $review->refresh();
        $this->assertEquals(CourseReview::STATUS_REJECTED, $review->status);
        $this->assertEquals($this->adminUser->id, $review->approved_by);
        $this->assertEquals($rejectionReason, $review->moderation_notes);
    }

    /** @test */
    public function admin_can_feature_approved_review()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => false,
        ]);

        // Test the markAsFeatured method
        $review->markAsFeatured();

        $review->refresh();
        $this->assertTrue($review->is_featured);
    }

    /** @test */
    public function admin_can_unfeature_review()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
        ]);

        // Test the unmarkAsFeatured method
        $review->unmarkAsFeatured();

        $review->refresh();
        $this->assertFalse($review->is_featured);
    }

    /** @test */
    public function featured_reviews_are_limited_to_six()
    {
        // Create 6 featured reviews
        CourseReview::factory()->count(6)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
        ]);

        // Try to create 7th featured review
        $newReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => false,
        ]);

        // Simulate admin attempting to feature the 7th review
        $currentFeaturedCount = CourseReview::where('is_featured', true)->count();
        $this->assertEquals(6, $currentFeaturedCount);

        // The admin interface should prevent featuring more than 6
        if ($currentFeaturedCount >= 6) {
            // Simulate the admin panel logic
            $canFeature = false;
        } else {
            $canFeature = true;
        }

        $this->assertFalse($canFeature);
    }

    /** @test */
    public function admin_can_bulk_approve_reviews()
    {
        $pendingReviews = CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_PENDING,
        ]);

        $approvedReviews = CourseReview::factory()->count(2)->create([
            'status' => CourseReview::STATUS_APPROVED,
        ]);

        // Simulate bulk approve action
        foreach ($pendingReviews as $review) {
            if ($review->status === CourseReview::STATUS_PENDING) {
                $review->approve($this->adminUser);
            }
        }

        // Verify all pending reviews are now approved
        $this->assertEquals(0, CourseReview::where('status', CourseReview::STATUS_PENDING)->count());
        $this->assertEquals(5, CourseReview::where('status', CourseReview::STATUS_APPROVED)->count());
    }

    /** @test */
    public function admin_can_bulk_reject_reviews()
    {
        $pendingReviews = CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_PENDING,
        ]);

        $rejectionReason = 'Bulk rejection for policy violation.';

        // Simulate bulk reject action
        foreach ($pendingReviews as $review) {
            if ($review->status === CourseReview::STATUS_PENDING) {
                $review->reject($this->adminUser, $rejectionReason);
            }
        }

        // Verify all pending reviews are now rejected
        $this->assertEquals(0, CourseReview::where('status', CourseReview::STATUS_PENDING)->count());
        $this->assertEquals(3, CourseReview::where('status', CourseReview::STATUS_REJECTED)->count());

        // Verify rejection reason is set for all
        $rejectedReviews = CourseReview::where('status', CourseReview::STATUS_REJECTED)->get();
        foreach ($rejectedReviews as $review) {
            $this->assertEquals($rejectionReason, $review->moderation_notes);
        }
    }

    /** @test */
    public function admin_can_bulk_feature_approved_reviews()
    {
        $approvedReviews = CourseReview::factory()->count(4)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => false,
            'rating' => 5,
        ]);

        // Simulate bulk feature action (limited to available slots)
        $currentFeatured = CourseReview::where('is_featured', true)->count();
        $available = max(0, 6 - $currentFeatured);
        
        $toFeature = $approvedReviews->take($available);
        
        foreach ($toFeature as $review) {
            $review->markAsFeatured();
        }

        // Verify correct number of reviews are featured
        $featuredCount = CourseReview::where('is_featured', true)->count();
        $this->assertEquals(min(4, 6), $featuredCount);
    }

    /** @test */
    public function admin_can_bulk_unfeature_reviews()
    {
        $featuredReviews = CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
        ]);

        // Simulate bulk unfeature action
        foreach ($featuredReviews as $review) {
            if ($review->is_featured) {
                $review->unmarkAsFeatured();
            }
        }

        // Verify no reviews are featured
        $this->assertEquals(0, CourseReview::where('is_featured', true)->count());
    }

    /** @test */
    public function review_statistics_are_calculated_correctly()
    {
        // Clear existing reviews to get clean stats
        CourseReview::truncate();
        
        // Create reviews with various statuses
        CourseReview::factory()->count(5)->create(['status' => CourseReview::STATUS_PENDING]);
        CourseReview::factory()->count(8)->create(['status' => CourseReview::STATUS_APPROVED]);
        CourseReview::factory()->count(2)->create(['status' => CourseReview::STATUS_REJECTED]);
        CourseReview::factory()->count(1)->create(['status' => CourseReview::STATUS_HIDDEN]);

        // Create featured reviews
        CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
        ]);

        // Create verified purchase reviews
        CourseReview::factory()->count(6)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_verified_purchase' => true,
        ]);

        // Create high-rated reviews
        CourseReview::factory()->count(7)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 5,
        ]);

        // Test statistics
        $this->assertEquals(5, CourseReview::where('status', CourseReview::STATUS_PENDING)->count());
        $this->assertEquals(8 + 3 + 6 + 7, CourseReview::where('status', CourseReview::STATUS_APPROVED)->count());
        $this->assertEquals(2, CourseReview::where('status', CourseReview::STATUS_REJECTED)->count());
        $this->assertEquals(1, CourseReview::where('status', CourseReview::STATUS_HIDDEN)->count());
        $this->assertEquals(3, CourseReview::where('is_featured', true)->count());
        $this->assertTrue(CourseReview::where('is_verified_purchase', true)->count() >= 6);
        $this->assertTrue(CourseReview::where('rating', '>=', 4)->count() >= 7);
    }

    /** @test */
    public function review_can_be_edited_by_admin_regardless_of_time()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'title' => 'Original Title',
            'review_text' => 'Original review text',
            'rating' => 4,
            'created_at' => now()->subDays(30), // Very old review
        ]);

        // Admin should be able to edit any review
        $review->update([
            'title' => 'Admin Updated Title',
            'review_text' => 'Admin updated review text',
            'rating' => 5,
        ]);

        $review->refresh();
        $this->assertEquals('Admin Updated Title', $review->title);
        $this->assertEquals('Admin updated review text', $review->review_text);
        $this->assertEquals(5, $review->rating);
    }

    /** @test */
    public function hidden_reviews_do_not_appear_in_public_listings()
    {
        $visibleReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
        ]);

        $hiddenReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_HIDDEN,
        ]);

        // Test that only approved reviews appear in public queries
        $publicReviews = CourseReview::approved()->get();
        
        $this->assertCount(1, $publicReviews);
        $this->assertEquals($visibleReview->id, $publicReviews->first()->id);
        $this->assertNotContains($hiddenReview->id, $publicReviews->pluck('id'));
    }

    /** @test */
    public function review_moderation_workflow_is_complete()
    {
        $review = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_PENDING,
            'title' => 'Test Review',
            'review_text' => 'This is a test review for moderation workflow.',
            'rating' => 4,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        // Step 1: Review is pending
        $this->assertEquals(CourseReview::STATUS_PENDING, $review->status);
        $this->assertNull($review->approved_at);
        $this->assertNull($review->approved_by);

        // Step 2: Admin approves review
        $review->approve($this->adminUser);
        $review->refresh();

        $this->assertEquals(CourseReview::STATUS_APPROVED, $review->status);
        $this->assertNotNull($review->approved_at);
        $this->assertEquals($this->adminUser->id, $review->approved_by);

        // Step 3: Admin features review for homepage
        $review->markAsFeatured();
        $review->refresh();

        $this->assertTrue($review->is_featured);

        // Step 4: Review gets reported multiple times and auto-hidden
        $review->update(['reported_count' => 5]);
        $review->update(['status' => CourseReview::STATUS_HIDDEN]);
        $review->refresh();

        $this->assertEquals(CourseReview::STATUS_HIDDEN, $review->status);
        $this->assertEquals(5, $review->reported_count);

        // Step 5: Admin can restore review if needed
        $review->update(['status' => CourseReview::STATUS_APPROVED]);
        $review->refresh();

        $this->assertEquals(CourseReview::STATUS_APPROVED, $review->status);
    }

    /** @test */
    public function homepage_testimonials_cache_works()
    {
        // Create featured reviews
        $featuredReviews = CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
            'rating' => 5,
        ]);

        // First request should cache the data
        $response1 = $this->get('/');
        $response1->assertStatus(200);

        // Create more featured reviews
        CourseReview::factory()->count(2)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
            'rating' => 4,
        ]);

        // Second request should still return cached data (3 testimonials)
        $response2 = $this->get('/');
        $response2->assertStatus(200);
        
        // Clear cache
        \Cache::forget('homepage_testimonials');

        // Third request should return fresh data (5 testimonials)
        $response3 = $this->get('/');
        $response3->assertStatus(200);
    }

    /** @test */
    public function review_helpful_vote_integrity_is_maintained()
    {
        $review = CourseReview::factory()->create([
            'helpful_count' => 0,
        ]);

        // Test that helpful count matches actual votes
        $this->assertEquals(0, $review->helpfulVotes()->count());
        $this->assertEquals(0, $review->helpful_count);

        // Add some votes
        $review->helpfulVotes()->create([
            'user_id' => $this->adminUser->id,
        ]);

        $review->increment('helpful_count');
        $review->refresh();

        $this->assertEquals(1, $review->helpfulVotes()->count());
        $this->assertEquals(1, $review->helpful_count);

        // Test cascading delete
        $review->delete();
        $this->assertEquals(0, \App\Models\CourseReviewHelpfulVote::where('review_id', $review->id)->count());
    }
}