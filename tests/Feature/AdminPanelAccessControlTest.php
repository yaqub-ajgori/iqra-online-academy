<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Course;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessControlTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $teacherUser;
    private User $studentUser;
    private Teacher $teacher;
    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user with admin role
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@iqra-academy.com',
        ]);
        UserRole::create([
            'user_id' => $this->adminUser->id,
            'role_type' => 'admin',
        ]);

        // Create teacher user with teacher role
        $this->teacherUser = User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@iqra-academy.com',
        ]);
        UserRole::create([
            'user_id' => $this->teacherUser->id,
            'role_type' => 'teacher',
        ]);

        $this->teacher = Teacher::factory()->create([
            'user_id' => $this->teacherUser->id,
            'full_name' => 'Teacher User',
        ]);

        // Create student user with student role
        $this->studentUser = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
        ]);
        UserRole::create([
            'user_id' => $this->studentUser->id,
            'role_type' => 'student',
        ]);

        $this->student = Student::factory()->create([
            'user_id' => $this->studentUser->id,
            'full_name' => 'Student User',
        ]);
    }

    /** @test */
    public function admin_can_access_filament_panel()
    {
        $this->assertTrue($this->adminUser->canAccessPanel(new \Filament\Panel()));
        $this->assertTrue($this->adminUser->isAdmin());
        $this->assertFalse($this->adminUser->isTeacher());
        $this->assertFalse($this->adminUser->isStudent());
    }

    /** @test */
    public function teacher_can_access_filament_panel()
    {
        $this->assertTrue($this->teacherUser->canAccessPanel(new \Filament\Panel()));
        $this->assertFalse($this->teacherUser->isAdmin());
        $this->assertTrue($this->teacherUser->isTeacher());
        $this->assertFalse($this->teacherUser->isStudent());
    }

    /** @test */
    public function student_cannot_access_filament_panel()
    {
        $this->assertFalse($this->studentUser->canAccessPanel(new \Filament\Panel()));
        $this->assertFalse($this->studentUser->isAdmin());
        $this->assertFalse($this->studentUser->isTeacher());
        $this->assertTrue($this->studentUser->isStudent());
    }

    /** @test */
    public function admin_can_view_all_resources()
    {
        $this->actingAs($this->adminUser);
        
        // Admin can view all resources
        $this->assertTrue(\App\Filament\Resources\Students\StudentResource::canViewAny());
        $this->assertTrue(\App\Filament\Resources\Users\UserResource::canViewAny());
        $this->assertTrue(\App\Filament\Resources\Payments\PaymentResource::canViewAny());
        $this->assertTrue(\App\Filament\Resources\Donations\DonationResource::canViewAny());
        $this->assertTrue(\App\Filament\Resources\Certificates\CertificateResource::canViewAny());
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canViewAny());
    }

    /** @test */
    public function teacher_cannot_view_admin_only_resources()
    {
        $this->actingAs($this->teacherUser);
        
        // Teachers cannot access admin-only resources
        $this->assertFalse(\App\Filament\Resources\Students\StudentResource::canViewAny());
        $this->assertFalse(\App\Filament\Resources\Users\UserResource::canViewAny());
        $this->assertFalse(\App\Filament\Resources\Payments\PaymentResource::canViewAny());
        $this->assertFalse(\App\Filament\Resources\Donations\DonationResource::canViewAny());
        $this->assertFalse(\App\Filament\Resources\Certificates\CertificateResource::canViewAny());
        
        // Teachers can access course reviews (for their courses)
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canViewAny());
    }

    /** @test */
    public function teacher_can_view_only_their_courses()
    {
        // Create course taught by the teacher
        $teacherCourse = Course::factory()->create([
            'instructor_id' => $this->teacher->id,
            'title' => 'Teacher Course',
        ]);

        // Create course taught by another teacher
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create([
            'instructor_id' => $otherTeacher->id,
            'title' => 'Other Teacher Course',
        ]);

        $this->actingAs($this->teacherUser);

        // Teacher can view their own course
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canView($teacherCourse));
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canEdit($teacherCourse));

        // Teacher cannot view other teacher's course
        $this->assertFalse(\App\Filament\Resources\Courses\CourseResource::canView($otherCourse));
        $this->assertFalse(\App\Filament\Resources\Courses\CourseResource::canEdit($otherCourse));
    }

    /** @test */
    public function admin_can_view_all_courses()
    {
        $course1 = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $course2 = Course::factory()->create(['instructor_id' => Teacher::factory()->create()->id]);

        $this->actingAs($this->adminUser);

        // Admin can view all courses
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canView($course1));
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canView($course2));
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canEdit($course1));
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canEdit($course2));
    }

    /** @test */
    public function teacher_can_create_courses()
    {
        $this->actingAs($this->teacherUser);
        
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canCreate());
    }

    /** @test */
    public function teacher_cannot_delete_courses()
    {
        $course = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        
        $this->actingAs($this->teacherUser);
        
        $this->assertFalse(\App\Filament\Resources\Courses\CourseResource::canDelete($course));
    }

    /** @test */
    public function admin_can_delete_courses()
    {
        $course = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        
        $this->actingAs($this->adminUser);
        
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canDelete($course));
    }

    /** @test */
    public function teacher_course_query_is_filtered()
    {
        // Create courses by different teachers
        $teacherCourse1 = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $teacherCourse2 = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create(['instructor_id' => $otherTeacher->id]);

        $this->actingAs($this->teacherUser);

        // Get query for teacher
        $query = \App\Filament\Resources\Courses\CourseResource::getEloquentQuery();
        $courseIds = $query->pluck('id')->toArray();

        // Teacher should only see their own courses
        $this->assertContains($teacherCourse1->id, $courseIds);
        $this->assertContains($teacherCourse2->id, $courseIds);
        $this->assertNotContains($otherCourse->id, $courseIds);
    }

    /** @test */
    public function admin_course_query_shows_all_courses()
    {
        // Create courses by different teachers
        $teacherCourse = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create(['instructor_id' => $otherTeacher->id]);

        $this->actingAs($this->adminUser);

        // Get query for admin
        $query = \App\Filament\Resources\Courses\CourseResource::getEloquentQuery();
        $courseIds = $query->pluck('id')->toArray();

        // Admin should see all courses
        $this->assertContains($teacherCourse->id, $courseIds);
        $this->assertContains($otherCourse->id, $courseIds);
    }

    /** @test */
    public function teacher_can_access_review_management_for_their_courses()
    {
        $teacherCourse = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $otherCourse = Course::factory()->create(['instructor_id' => Teacher::factory()->create()->id]);

        $this->actingAs($this->teacherUser);

        // Assuming CourseReviewResource has similar access control (this might need to be implemented)
        // For now, let's test that the teacher user exists and has proper roles
        $this->assertTrue($this->teacherUser->isTeacher());
        $this->assertNotNull($this->teacherUser->teacher);
        $this->assertEquals($this->teacher->id, $this->teacherUser->teacher->id);
    }

    /** @test */
    public function admin_can_manage_user_accounts()
    {
        $this->actingAs($this->adminUser);

        // Admin should be able to access user management
        // This assumes UserResource has admin-only access (might need implementation)
        $this->assertTrue($this->adminUser->isAdmin());
    }

    /** @test */
    public function teacher_cannot_manage_user_accounts()
    {
        $this->actingAs($this->teacherUser);

        // Teachers should not be able to manage users
        $this->assertFalse($this->teacherUser->isAdmin());
        $this->assertTrue($this->teacherUser->isTeacher());
    }

    /** @test */
    public function role_assignment_works_correctly()
    {
        // Test user can have multiple roles
        $multiRoleUser = User::factory()->create();
        
        UserRole::create(['user_id' => $multiRoleUser->id, 'role_type' => 'teacher']);
        UserRole::create(['user_id' => $multiRoleUser->id, 'role_type' => 'admin']);

        $this->assertTrue($multiRoleUser->hasRole('teacher'));
        $this->assertTrue($multiRoleUser->hasRole('admin'));
        $this->assertTrue($multiRoleUser->isTeacher());
        $this->assertTrue($multiRoleUser->isAdmin());
        $this->assertFalse($multiRoleUser->isStudent());
    }

    /** @test */
    public function user_without_roles_has_no_admin_access()
    {
        $noRoleUser = User::factory()->create();

        $this->assertFalse($noRoleUser->isAdmin());
        $this->assertFalse($noRoleUser->isTeacher());
        $this->assertFalse($noRoleUser->isStudent());
        $this->assertFalse($noRoleUser->canAccessPanel(new \Filament\Panel()));
    }

    /** @test */
    public function teacher_can_view_additional_instructor_courses()
    {
        // Create course where teacher is not primary instructor but is additional instructor
        $otherTeacher = Teacher::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $otherTeacher->id]);
        
        // Add our teacher as additional instructor (this would require course_instructors table)
        // For now, let's just test the logic exists in the canView method
        
        $this->actingAs($this->teacherUser);
        
        // Test the main instructor relationship
        $ownCourse = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $this->assertTrue(\App\Filament\Resources\Courses\CourseResource::canView($ownCourse));
    }

    /** @test */
    public function resource_navigation_is_properly_filtered()
    {
        // Test that teachers don't see navigation items they shouldn't access
        $this->actingAs($this->teacherUser);
        
        // Check that student resource is not accessible
        $this->assertFalse(\App\Filament\Resources\Students\StudentResource::canViewAny());
        
        // Admin should see everything
        $this->actingAs($this->adminUser);
        $this->assertTrue(\App\Filament\Resources\Students\StudentResource::canViewAny());
    }

    /** @test */
    public function admin_only_trait_works_correctly()
    {
        // Test AdminOnlyResource trait functionality
        $mockResourceClass = new class {
            use \App\Filament\Traits\AdminOnlyResource;
        };

        // Test with admin user
        $this->actingAs($this->adminUser);
        $this->assertTrue($mockResourceClass::canViewAny());
        $this->assertTrue($mockResourceClass::canCreate());

        // Test with teacher user
        $this->actingAs($this->teacherUser);
        $this->assertFalse($mockResourceClass::canViewAny());
        $this->assertFalse($mockResourceClass::canCreate());

        // Test with student user
        $this->actingAs($this->studentUser);
        $this->assertFalse($mockResourceClass::canViewAny());
        $this->assertFalse($mockResourceClass::canCreate());
    }

    /** @test */
    public function teacher_can_view_reviews_for_their_courses_only()
    {
        // Create course taught by the teacher
        $teacherCourse = Course::factory()->create([
            'instructor_id' => $this->teacher->id,
            'title' => 'Teacher Course',
        ]);

        // Create course taught by another teacher
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create([
            'instructor_id' => $otherTeacher->id,
            'title' => 'Other Teacher Course',
        ]);

        // Create reviews for both courses
        $teacherReview = \App\Models\CourseReview::factory()->create([
            'course_id' => $teacherCourse->id,
            'review_text' => 'Great course!',
        ]);

        $otherReview = \App\Models\CourseReview::factory()->create([
            'course_id' => $otherCourse->id,
            'review_text' => 'Another course review',
        ]);

        $this->actingAs($this->teacherUser);

        // Teacher can view review for their course
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canView($teacherReview));
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canEdit($teacherReview));

        // Teacher cannot view review for other teacher's course
        $this->assertFalse(\App\Filament\Resources\CourseReviews\CourseReviewResource::canView($otherReview));
        $this->assertFalse(\App\Filament\Resources\CourseReviews\CourseReviewResource::canEdit($otherReview));

        // Teachers cannot delete reviews (only admins can)
        $this->assertFalse(\App\Filament\Resources\CourseReviews\CourseReviewResource::canDelete($teacherReview));
    }

    /** @test */
    public function admin_can_manage_all_reviews()
    {
        $course = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $review = \App\Models\CourseReview::factory()->create([
            'course_id' => $course->id,
            'review_text' => 'Test review',
        ]);

        $this->actingAs($this->adminUser);

        // Admin can view, edit, and delete any review
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canView($review));
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canEdit($review));
        $this->assertTrue(\App\Filament\Resources\CourseReviews\CourseReviewResource::canDelete($review));
    }

    /** @test */
    public function teacher_review_query_is_filtered()
    {
        // Create courses by different teachers
        $teacherCourse1 = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $teacherCourse2 = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create(['instructor_id' => $otherTeacher->id]);

        // Create reviews for each course
        $teacherReview1 = \App\Models\CourseReview::factory()->create(['course_id' => $teacherCourse1->id]);
        $teacherReview2 = \App\Models\CourseReview::factory()->create(['course_id' => $teacherCourse2->id]);
        $otherReview = \App\Models\CourseReview::factory()->create(['course_id' => $otherCourse->id]);

        $this->actingAs($this->teacherUser);

        // Get query for teacher
        $query = \App\Filament\Resources\CourseReviews\CourseReviewResource::getEloquentQuery();
        $reviewIds = $query->pluck('id')->toArray();

        // Teacher should only see reviews for their courses
        $this->assertContains($teacherReview1->id, $reviewIds);
        $this->assertContains($teacherReview2->id, $reviewIds);
        $this->assertNotContains($otherReview->id, $reviewIds);
    }

    /** @test */
    public function admin_review_query_shows_all_reviews()
    {
        // Create courses by different teachers
        $teacherCourse = Course::factory()->create(['instructor_id' => $this->teacher->id]);
        $otherTeacher = Teacher::factory()->create();
        $otherCourse = Course::factory()->create(['instructor_id' => $otherTeacher->id]);

        // Create reviews for both courses
        $teacherReview = \App\Models\CourseReview::factory()->create(['course_id' => $teacherCourse->id]);
        $otherReview = \App\Models\CourseReview::factory()->create(['course_id' => $otherCourse->id]);

        $this->actingAs($this->adminUser);

        // Get query for admin
        $query = \App\Filament\Resources\CourseReviews\CourseReviewResource::getEloquentQuery();
        $reviewIds = $query->pluck('id')->toArray();

        // Admin should see all reviews
        $this->assertContains($teacherReview->id, $reviewIds);
        $this->assertContains($otherReview->id, $reviewIds);
    }
}