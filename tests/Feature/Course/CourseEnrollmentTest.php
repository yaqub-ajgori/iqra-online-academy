<?php

use App\Models\{User, Student, Teacher, Course, CourseCategory, CourseEnrollment, Payment};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->student = Student::factory()->create();
    $this->user = User::factory()->create();
    $this->student->update(['user_id' => $this->user->id]);
    
    $this->teacher = Teacher::factory()->create();
    $this->category = CourseCategory::factory()->create();
    $this->course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
        'status' => 'published',
        'price' => 1000,
        'is_free' => false,
    ]);
});

test('student can view course details page', function () {
    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}");

    expect($response->status())->toBe(200);
});

test('student can enroll in free course', function () {
    $this->course->update(['is_free' => true, 'price' => 0]);

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/enroll");

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('course_enrollments', [
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'enrollment_type' => 'free',
    ]);
});

test('student cannot enroll in paid course without payment', function () {
    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/enroll");

    expect($response->status())->toBeIn([402, 403, 302]); // Payment required
});

test('student can enroll in paid course with valid payment', function () {
    // Create successful payment first
    $payment = Payment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'amount' => $this->course->price,
        'status' => 'completed',
    ]);

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/enroll", [
        'payment_id' => $payment->id,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('course_enrollments', [
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'payment_id' => $payment->id,
        'enrollment_type' => 'paid',
    ]);
});

test('student cannot enroll in same course twice', function () {
    // Create existing enrollment
    CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
    ]);

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/enroll");

    expect($response->status())->toBeIn([409, 422, 302]); // Conflict or validation error
});

test('student can access enrolled course', function () {
    CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->user)->get("/learn/courses/{$this->course->id}");

    expect($response->status())->toBe(200);
});

test('student cannot access non-enrolled course learning page', function () {
    $response = $this->actingAs($this->user)->get("/learn/courses/{$this->course->id}");

    expect($response->status())->toBeIn([403, 302]);
});

test('enrollment tracks progress correctly', function () {
    $enrollment = CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'progress_percentage' => 0,
        'lessons_completed' => 0,
    ]);

    // Simulate progress update
    $enrollment->update([
        'progress_percentage' => 50,
        'lessons_completed' => 5,
    ]);

    $this->assertDatabaseHas('course_enrollments', [
        'id' => $enrollment->id,
        'progress_percentage' => 50,
        'lessons_completed' => 5,
    ]);
});

test('enrollment can be completed', function () {
    $enrollment = CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_completed' => false,
    ]);

    // Complete enrollment
    $enrollment->update([
        'is_completed' => true,
        'progress_percentage' => 100,
    ]);

    $this->assertDatabaseHas('course_enrollments', [
        'id' => $enrollment->id,
        'is_completed' => true,
        'progress_percentage' => 100,
    ]);
});

test('student can view their enrolled courses', function () {
    CourseEnrollment::factory()->count(3)->create([
        'student_id' => $this->student->id,
    ]);

    $response = $this->actingAs($this->user)->get('/student/courses');

    expect($response->status())->toBe(200);
});

test('enrollment can be deactivated by admin', function () {
    $enrollment = CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_active' => true,
    ]);

    // Admin deactivates enrollment
    $admin = User::factory()->create(['email' => 'admin@test.com']);
    $response = $this->actingAs($admin)->put("/admin/course-enrollments/{$enrollment->id}", [
        'student_id' => $enrollment->student_id,
        'course_id' => $enrollment->course_id,
        'enrolled_at' => $enrollment->enrolled_at,
        'enrollment_type' => $enrollment->enrollment_type,
        'progress_percentage' => $enrollment->progress_percentage,
        'lessons_completed' => $enrollment->lessons_completed,
        'is_completed' => $enrollment->is_completed,
        'is_active' => false,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('course_enrollments', [
        'id' => $enrollment->id,
        'is_active' => false,
    ]);
});

test('inactive enrollment prevents course access', function () {
    CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_active' => false,
    ]);

    $response = $this->actingAs($this->user)->get("/learn/courses/{$this->course->id}");

    expect($response->status())->toBeIn([403, 302]);
});

test('enrollment supports different types', function () {
    $types = ['paid', 'free', 'scholarship', 'trial'];
    
    foreach ($types as $type) {
        $enrollment = CourseEnrollment::factory()->create([
            'student_id' => $this->student->id,
            'enrollment_type' => $type,
        ]);
        
        $this->assertDatabaseHas('course_enrollments', [
            'id' => $enrollment->id,
            'enrollment_type' => $type,
        ]);
    }
});

test('student can unenroll from course if allowed', function () {
    $enrollment = CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->user)->delete("/courses/{$this->course->id}/unenroll");

    expect($response->status())->toBeIn([200, 302]);
    // Should either delete or deactivate enrollment
    $enrollment->refresh();
    expect($enrollment->is_active)->toBeFalse();
});