<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseReview;
use App\Models\CourseReviewHelpfulVote;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $studentUser;
    private Student $student;
    private Course $course;
    private CourseEnrollment $enrollment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@iqra-academy.com',
        ]);

        // Create student user and student
        $this->studentUser = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);

        $this->student = Student::factory()->create([
            'user_id' => $this->studentUser->id,
            'full_name' => 'Test Student',
        ]);

        // Create course
        $this->course = Course::factory()->create([
            'title' => 'কুরআন তিলাওয়াত কোর্স',
            'status' => 'published',
        ]);

        // Create enrollment
        $this->enrollment = CourseEnrollment::factory()->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function enrolled_student_can_submit_review()
    {
        $this->actingAs($this->studentUser);

        $reviewData = [
            'title' => 'অসাধারণ কোর্স',
            'review_text' => 'মাশাআল্লাহ! এই কোর্সটি আমার জীবনে অনেক ইতিবাচক পরিবর্তন এনেছে।',
            'rating' => 5,
        ];

        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), $reviewData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('course_reviews', [
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'user_id' => $this->studentUser->id,
            'title' => $reviewData['title'],
            'review_text' => $reviewData['review_text'],
            'rating' => $reviewData['rating'],
            'status' => CourseReview::STATUS_PENDING,
            'is_verified_purchase' => true,
        ]);
    }

    /** @test */
    public function non_enrolled_student_cannot_submit_review()
    {
        // Create a student not enrolled in the course
        $nonEnrolledUser = User::factory()->create();
        $nonEnrolledStudent = Student::factory()->create([
            'user_id' => $nonEnrolledUser->id,
            'full_name' => 'Non Enrolled Student',
        ]);

        $this->actingAs($nonEnrolledUser);

        $reviewData = [
            'title' => 'Test Review',
            'review_text' => 'This should not be allowed.',
            'rating' => 5,
        ];

        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), $reviewData);

        $response->assertSessionHasErrors(['enrollment']);
        
        $this->assertDatabaseMissing('course_reviews', [
            'course_id' => $this->course->id,
            'student_id' => $nonEnrolledStudent->id,
        ]);
    }

    /** @test */
    public function student_cannot_submit_duplicate_review()
    {
        // Create existing review
        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'user_id' => $this->studentUser->id,
        ]);

        $this->actingAs($this->studentUser);

        $reviewData = [
            'title' => 'Duplicate Review',
            'review_text' => 'This should be rejected.',
            'rating' => 5,
        ];

        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), $reviewData);

        $response->assertSessionHasErrors(['duplicate']);
        
        // Should still have only one review
        $this->assertEquals(1, CourseReview::where('course_id', $this->course->id)
            ->where('student_id', $this->student->id)->count());
    }

    /** @test */
    public function student_can_edit_pending_review_within_24_hours()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'user_id' => $this->studentUser->id,
            'status' => CourseReview::STATUS_PENDING,
            'created_at' => now()->subHours(12), // 12 hours ago
        ]);

        $this->actingAs($this->studentUser);

        $updatedData = [
            'title' => 'Updated Title',
            'review_text' => 'Updated review text.',
            'rating' => 4,
        ];

        $response = $this->put(
            route('frontend.courses.reviews.update', [$this->course->slug, $review->id]), 
            $updatedData
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $review->refresh();
        $this->assertEquals($updatedData['title'], $review->title);
        $this->assertEquals($updatedData['review_text'], $review->review_text);
        $this->assertEquals($updatedData['rating'], $review->rating);
    }

    /** @test */
    public function student_cannot_edit_review_after_24_hours()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'user_id' => $this->studentUser->id,
            'status' => CourseReview::STATUS_PENDING,
            'created_at' => now()->subHours(25), // 25 hours ago
        ]);

        $this->actingAs($this->studentUser);

        $updatedData = [
            'title' => 'Updated Title',
            'review_text' => 'Updated review text.',
            'rating' => 4,
        ];

        $response = $this->put(
            route('frontend.courses.reviews.update', [$this->course->slug, $review->id]), 
            $updatedData
        );

        $response->assertSessionHasErrors(['edit']);
    }

    /** @test */
    public function student_cannot_edit_approved_review()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'user_id' => $this->studentUser->id,
            'status' => CourseReview::STATUS_APPROVED,
            'created_at' => now()->subHours(12),
        ]);

        $this->actingAs($this->studentUser);

        $updatedData = [
            'title' => 'Updated Title',
            'review_text' => 'Updated review text.',
            'rating' => 4,
        ];

        $response = $this->put(
            route('frontend.courses.reviews.update', [$this->course->slug, $review->id]), 
            $updatedData
        );

        $response->assertSessionHasErrors(['edit']);
    }

    /** @test */
    public function review_validation_works_correctly()
    {
        $this->actingAs($this->studentUser);

        // Test missing required fields
        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), []);
        $response->assertSessionHasErrors(['review_text', 'rating']);

        // Test invalid rating
        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), [
            'review_text' => 'Valid review text',
            'rating' => 6, // Invalid rating (should be 1-5)
        ]);
        $response->assertSessionHasErrors(['rating']);

        // Test review text too long
        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), [
            'review_text' => str_repeat('A', 2001), // Exceeds 2000 character limit
            'rating' => 5,
        ]);
        $response->assertSessionHasErrors(['review_text']);

        // Test title too long
        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), [
            'title' => str_repeat('A', 256), // Exceeds 255 character limit
            'review_text' => 'Valid review text',
            'rating' => 5,
        ]);
        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function course_reviews_index_displays_correctly()
    {
        // Create multiple reviews for the course
        $approvedReviews = CourseReview::factory()->count(3)->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
        ]);

        $pendingReview = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_PENDING,
        ]);

        $response = $this->get(route('frontend.courses.reviews', $this->course->slug));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Frontend/Reviews/Index')
                ->has('reviews.data', 3) // Only approved reviews shown
                ->has('course')
                ->has('ratingDistribution')
        );
    }

    /** @test */
    public function reviews_can_be_sorted_and_filtered()
    {
        // Create reviews with different ratings and helpful counts
        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 5,
            'helpful_count' => 10,
            'created_at' => now()->subDays(1),
        ]);

        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 4,
            'helpful_count' => 20,
            'created_at' => now()->subDays(2),
        ]);

        // Test sorting by helpful count
        $response = $this->get(route('frontend.courses.reviews', $this->course->slug) . '?sort=helpful');
        $response->assertStatus(200);

        // Test filtering by rating
        $response = $this->get(route('frontend.courses.reviews', $this->course->slug) . '?rating=5');
        $response->assertStatus(200);

        // Test sorting by recent
        $response = $this->get(route('frontend.courses.reviews', $this->course->slug) . '?sort=recent');
        $response->assertStatus(200);
    }

    /** @test */
    public function helpful_vote_system_works_correctly()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'helpful_count' => 0,
        ]);

        // Test authenticated user voting
        $this->actingAs($this->studentUser);
        $response = $this->post(route('frontend.courses.reviews.helpful', $review->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $review->refresh();
        $this->assertEquals(1, $review->helpful_count);

        $this->assertDatabaseHas('course_review_helpful_votes', [
            'review_id' => $review->id,
            'user_id' => $this->studentUser->id,
        ]);

        // Test duplicate voting prevention
        $response = $this->post(route('frontend.courses.reviews.helpful', $review->id));
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);

        // Count should remain 1
        $review->refresh();
        $this->assertEquals(1, $review->helpful_count);
    }

    /** @test */
    public function guest_can_vote_helpful_using_ip_and_session()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'helpful_count' => 0,
        ]);

        // Test guest voting
        $response = $this->withSession(['_token' => 'test-token'])
                        ->post(route('frontend.courses.reviews.helpful', $review->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $review->refresh();
        $this->assertEquals(1, $review->helpful_count);

        $this->assertDatabaseHas('course_review_helpful_votes', [
            'review_id' => $review->id,
            'user_id' => null,
        ]);
    }

    /** @test */
    public function review_reporting_system_works()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'reported_count' => 0,
        ]);

        $this->actingAs($this->studentUser);

        $reportData = [
            'reason' => 'spam',
            'details' => 'This review looks like spam content.',
        ];

        $response = $this->post(route('frontend.courses.reviews.report', $review->id), $reportData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $review->refresh();
        $this->assertEquals(1, $review->reported_count);
    }

    /** @test */
    public function review_auto_hides_after_multiple_reports()
    {
        $review = CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'reported_count' => 4, // One less than threshold
        ]);

        $this->actingAs($this->studentUser);

        $reportData = [
            'reason' => 'inappropriate',
            'details' => 'Inappropriate content.',
        ];

        $response = $this->post(route('frontend.courses.reviews.report', $review->id), $reportData);

        $response->assertStatus(200);

        $review->refresh();
        $this->assertEquals(5, $review->reported_count);
        $this->assertEquals(CourseReview::STATUS_HIDDEN, $review->status);
    }

    /** @test */
    public function homepage_displays_only_featured_reviews()
    {
        // Create approved reviews, some featured
        $featuredReviews = CourseReview::factory()->count(3)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
            'rating' => 5,
        ]);

        $nonFeaturedReviews = CourseReview::factory()->count(2)->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => false,
            'rating' => 4,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Frontend/HomePage')
                ->has('testimonials', 3) // Only featured reviews
        );
    }

    /** @test */
    public function featured_testimonials_api_returns_correct_data()
    {
        $featuredReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
            'title' => 'Great Course',
            'review_text' => 'This is a detailed review of the course content.',
            'rating' => 5,
        ]);

        $response = $this->get(route('api.testimonials'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'course',
                'review',
                'rating',
                'date',
                'verified',
            ]
        ]);

        $data = $response->json();
        $this->assertCount(1, $data);
        $this->assertEquals($featuredReview->id, $data[0]['id']);
        $this->assertEquals($featuredReview->student->full_name, $data[0]['name']);
    }

    /** @test */
    public function course_rating_statistics_are_calculated_correctly()
    {
        // Create reviews with different ratings
        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 5,
        ]);

        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 4,
        ]);

        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => 5,
        ]);

        // Pending review should not count
        CourseReview::factory()->create([
            'course_id' => $this->course->id,
            'status' => CourseReview::STATUS_PENDING,
            'rating' => 1,
        ]);

        $this->course->refresh();

        $this->assertEquals(4.67, round($this->course->average_rating, 2));
        $this->assertEquals(3, $this->course->total_reviews);

        $distribution = $this->course->rating_distribution;
        $this->assertEquals(2, $distribution[5]); // Two 5-star reviews
        $this->assertEquals(1, $distribution[4]); // One 4-star review
        $this->assertEquals(0, $distribution[3]); // No 3-star reviews
        $this->assertEquals(0, $distribution[2]); // No 2-star reviews
        $this->assertEquals(0, $distribution[1]); // No 1-star reviews (pending doesn't count)
    }

    /** @test */
    public function review_model_scopes_work_correctly()
    {
        // Create reviews with different statuses
        $approvedReview = CourseReview::factory()->create(['status' => CourseReview::STATUS_APPROVED]);
        $pendingReview = CourseReview::factory()->create(['status' => CourseReview::STATUS_PENDING]);
        $rejectedReview = CourseReview::factory()->create(['status' => CourseReview::STATUS_REJECTED]);
        
        // Create featured and verified reviews
        $featuredReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_featured' => true,
        ]);
        
        $verifiedReview = CourseReview::factory()->create([
            'status' => CourseReview::STATUS_APPROVED,
            'is_verified_purchase' => true,
        ]);

        // Test scopes
        $this->assertEquals(3, CourseReview::approved()->count()); // approved, featured, verified
        $this->assertEquals(1, CourseReview::featured()->count());
        $this->assertEquals(1, CourseReview::verifiedPurchase()->count());
        $this->assertEquals(3, CourseReview::highRated()->where('rating', '>=', 4)->count());
    }

    /** @test */
    public function review_helpful_vote_model_methods_work_correctly()
    {
        $review = CourseReview::factory()->create();

        // Test hasVoted method for authenticated user
        $this->assertFalse(CourseReviewHelpfulVote::hasVoted($review->id, $this->studentUser->id));

        CourseReviewHelpfulVote::create([
            'review_id' => $review->id,
            'user_id' => $this->studentUser->id,
        ]);

        $this->assertTrue(CourseReviewHelpfulVote::hasVoted($review->id, $this->studentUser->id));

        // Test hasVoted method for guest (IP-based)
        $guestIp = '192.168.1.1';
        $this->assertFalse(CourseReviewHelpfulVote::hasVoted($review->id, null, $guestIp));

        CourseReviewHelpfulVote::create([
            'review_id' => $review->id,
            'user_id' => null,
            'ip_address' => $guestIp,
        ]);

        $this->assertTrue(CourseReviewHelpfulVote::hasVoted($review->id, null, $guestIp));
    }

    /** @test */
    public function review_metadata_is_stored_correctly()
    {
        $this->actingAs($this->studentUser);

        // Update enrollment progress
        $this->enrollment->update([
            'progress_percentage' => 75,
            'is_completed' => false,
        ]);

        $reviewData = [
            'title' => 'Test Review',
            'review_text' => 'This is a test review.',
            'rating' => 4,
        ];

        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), $reviewData);

        $review = CourseReview::where('course_id', $this->course->id)
                             ->where('student_id', $this->student->id)
                             ->first();

        $this->assertNotNull($review->metadata);
        $this->assertEquals(75, $review->metadata['progress_at_review']);
        $this->assertFalse($review->metadata['completed_course']);
    }

    /** @test */
    public function verified_purchase_is_auto_detected()
    {
        $this->actingAs($this->studentUser);

        $reviewData = [
            'title' => 'Test Review',
            'review_text' => 'This is a test review.',
            'rating' => 4,
        ];

        $response = $this->post(route('frontend.courses.reviews.store', $this->course->slug), $reviewData);

        $review = CourseReview::where('course_id', $this->course->id)
                             ->where('student_id', $this->student->id)
                             ->first();

        $this->assertTrue($review->is_verified_purchase);
    }

    /** @test */
    public function bengali_date_formatting_works()
    {
        $review = CourseReview::factory()->create([
            'created_at' => '2024-07-15 10:30:00'
        ]);

        $bengaliDate = $review->bengali_date;
        
        $this->assertStringContains('জুলাই', $bengaliDate);
        $this->assertStringContains('15', $bengaliDate);
        $this->assertStringContains('2024', $bengaliDate);
    }

    /** @test */
    public function course_reviews_relationship_works()
    {
        $reviews = CourseReview::factory()->count(3)->create([
            'course_id' => $this->course->id,
        ]);

        $this->assertEquals(3, $this->course->reviews()->count());
        $this->assertEquals(3, $this->course->fresh()->reviews->count());
    }
}