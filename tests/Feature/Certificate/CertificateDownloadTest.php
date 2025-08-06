<?php

use App\Models\{User, Student, Teacher, Course, Certificate, CourseEnrollment};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('student can download their own certificate', function () {
    $student = Student::factory()->create();
    $user = User::factory()->create();
    $student->update(['user_id' => $user->id]);
    
    $certificate = Certificate::factory()->create([
        'student_id' => $student->id,
        'certificate_path' => 'certificates/test-certificate.pdf',
    ]);

    // Create fake PDF file for testing
    \Storage::disk('public')->put($certificate->certificate_path, 'fake pdf content');

    $response = $this->actingAs($user)->get("/certificates/{$certificate->id}/download");

    expect($response->status())->toBe(200);
    expect($response->headers->get('content-type'))->toContain('application/pdf');
});

test('student cannot download other students certificate', function () {
    $student1 = Student::factory()->create();
    $user1 = User::factory()->create();
    $student1->update(['user_id' => $user1->id]);
    
    $student2 = Student::factory()->create();
    $user2 = User::factory()->create();
    $student2->update(['user_id' => $user2->id]);
    
    $certificate = Certificate::factory()->create([
        'student_id' => $student2->id,
        'certificate_path' => 'certificates/test-certificate.pdf',
    ]);

    $response = $this->actingAs($user1)->get("/certificates/{$certificate->id}/download");

    expect($response->status())->toBe(403);
});

test('guest cannot download certificate', function () {
    $certificate = Certificate::factory()->create([
        'certificate_path' => 'certificates/test-certificate.pdf',
    ]);

    $response = $this->get("/certificates/{$certificate->id}/download");

    expect($response->status())->toBe(302); // Redirect to login
});

test('certificate download fails for missing file', function () {
    $student = Student::factory()->create();
    $user = User::factory()->create();
    $student->update(['user_id' => $user->id]);
    
    $certificate = Certificate::factory()->create([
        'student_id' => $student->id,
        'certificate_path' => 'certificates/missing-certificate.pdf',
    ]);

    $response = $this->actingAs($user)->get("/certificates/{$certificate->id}/download");

    expect($response->status())->toBe(404);
});

test('student can view their certificates list', function () {
    $student = Student::factory()->create();
    $user = User::factory()->create();
    $student->update(['user_id' => $user->id]);
    
    // Create some certificates for this student
    Certificate::factory()->count(3)->create(['student_id' => $student->id]);
    
    // Create certificate for another student (should not appear)
    Certificate::factory()->create();

    $response = $this->actingAs($user)->get('/student/certificates');

    expect($response->status())->toBe(200);
    // Additional assertions about page content would go here
});

test('certificate verification page works with valid code', function () {
    $certificate = Certificate::factory()->create([
        'is_verified' => true,
        'is_revoked' => false,
    ]);

    $response = $this->get("/certificates/verify?code={$certificate->verification_code}");

    expect($response->status())->toBe(200);
    // Additional assertions about certificate details would go here
});

test('certificate verification page shows error for invalid code', function () {
    $response = $this->get("/certificates/verify?code=INVALID12345");

    expect($response->status())->toBe(200);
    // Should show error message but not fail completely
});