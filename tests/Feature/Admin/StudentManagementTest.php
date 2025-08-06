<?php

use App\Models\{User, Student, Course, CourseEnrollment, Payment};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create admin user
    $this->admin = User::factory()->create(['email' => 'admin@test.com']);
});

test('admin can view students list page', function () {
    // Create some students
    Student::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)->get('/admin/students');

    expect($response->status())->toBe(200);
});

test('admin can view student creation page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/students/create');

    expect($response->status())->toBe(200);
});

test('admin can create new student', function () {
    $user = User::factory()->create();
    
    $studentData = [
        'user_id' => $user->id,
        'full_name' => 'Test Student',
        'phone' => '01712345678',
        'date_of_birth' => '1995-01-01',
        'gender' => 'male',
        'address' => 'Test Address',
        'city' => 'Dhaka',
        'district' => 'Dhaka',
        'country' => 'Bangladesh',
        'postal_code' => '1000',
        'occupation' => 'Student',
        'education_level' => 'undergraduate',
        'is_active' => true,
        'is_verified' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/students', $studentData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('students', [
        'full_name' => 'Test Student',
        'phone' => '01712345678',
    ]);
});

test('admin can view student edit page', function () {
    $student = Student::factory()->create();

    $response = $this->actingAs($this->admin)->get("/admin/students/{$student->id}/edit");

    expect($response->status())->toBe(200);
});

test('admin can update student', function () {
    $student = Student::factory()->create([
        'full_name' => 'Original Name',
    ]);

    $updateData = [
        'user_id' => $student->user_id,
        'full_name' => 'Updated Student Name',
        'phone' => $student->phone,
        'date_of_birth' => $student->date_of_birth,
        'gender' => 'female',
        'address' => 'Updated Address',
        'city' => $student->city,
        'district' => $student->district,
        'country' => $student->country,
        'postal_code' => $student->postal_code,
        'occupation' => 'Teacher',
        'education_level' => 'graduate',
        'is_active' => true,
        'is_verified' => false,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/students/{$student->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'full_name' => 'Updated Student Name',
        'gender' => 'female',
        'occupation' => 'Teacher',
        'is_verified' => false,
    ]);
});

test('admin can deactivate student', function () {
    $student = Student::factory()->create(['is_active' => true]);

    $updateData = array_merge($student->toArray(), [
        'is_active' => false,
    ]);

    $response = $this->actingAs($this->admin)->put("/admin/students/{$student->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'is_active' => false,
    ]);
});

test('admin can view student enrollments', function () {
    $student = Student::factory()->create();
    CourseEnrollment::factory()->count(3)->create(['student_id' => $student->id]);

    $response = $this->actingAs($this->admin)->get("/admin/students/{$student->id}");

    expect($response->status())->toBe(200);
});

test('admin can manage student enrollments', function () {
    $student = Student::factory()->create();
    $course = Course::factory()->create();

    $enrollmentData = [
        'student_id' => $student->id,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'enrollment_type' => 'paid',
        'progress_percentage' => 0,
        'is_completed' => false,
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/course-enrollments', $enrollmentData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('course_enrollments', [
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);
});

test('admin can view student payments', function () {
    $student = Student::factory()->create();
    Payment::factory()->count(2)->create(['student_id' => $student->id]);

    $response = $this->actingAs($this->admin)->get('/admin/payments');

    expect($response->status())->toBe(200);
});

test('student validation works correctly', function () {
    $invalidData = [
        'full_name' => '', // Required field empty
        'phone' => 'invalid_phone', // Invalid phone format
        'email' => 'invalid_email', // Invalid email format
        'gender' => 'invalid_gender', // Invalid enum value
    ];

    $response = $this->actingAs($this->admin)->post('/admin/students', $invalidData);

    expect($response->status())->toBeIn([422, 302]); // Validation error or redirect back
});

test('admin can bulk update student status', function () {
    $students = Student::factory()->count(3)->create(['is_active' => true]);

    // Test bulk deactivation functionality if available
    foreach ($students as $student) {
        $response = $this->actingAs($this->admin)->put("/admin/students/{$student->id}", 
            array_merge($student->toArray(), ['is_active' => false])
        );
        expect($response->status())->toBeIn([200, 302]);
    }

    // Verify all students are deactivated
    foreach ($students as $student) {
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'is_active' => false,
        ]);
    }
});