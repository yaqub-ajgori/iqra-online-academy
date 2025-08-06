<?php

use App\Models\{User, Student, Teacher, Course, CourseEnrollment, Certificate};
use App\Services\CertificateService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->certificateService = app(CertificateService::class);
});

test('certificate can be generated for completed course', function () {
    // Create test data
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $teacher->id]);
    $student = Student::factory()->create();
    
    // Create completed enrollment
    $enrollment = CourseEnrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'is_completed' => true,
        'progress_percentage' => 100,
    ]);

    // Generate certificate
    $certificate = $this->certificateService->generateCertificate($student, $course, 85.5);

    // Refresh certificate to get database values
    $certificate->refresh();

    // Assertions
    expect($certificate)->toBeInstanceOf(Certificate::class);
    expect($certificate->student_id)->toBe($student->id);
    expect($certificate->course_id)->toBe($course->id);
    expect($certificate->student_name)->toBe($student->full_name);
    expect($certificate->course_title)->toBe($course->title);
    expect((float)$certificate->final_score)->toBe(85.5);
    expect($certificate->certificate_number)->toStartWith('IOA-');
    expect($certificate->verification_code)->toHaveLength(12);
    expect($certificate->is_verified)->toBeTrue();
    expect($certificate->is_revoked)->toBeFalse();
});

test('certificate cannot be generated for incomplete course', function () {
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $teacher->id]);
    $student = Student::factory()->create();
    
    // Create incomplete enrollment
    CourseEnrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'is_completed' => false,
        'progress_percentage' => 75,
    ]);

    // Check eligibility
    $isEligible = $this->certificateService->isEligibleForCertificate($student, $course);

    expect($isEligible)->toBeFalse();
});

test('duplicate certificates cannot be generated', function () {
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $teacher->id]);
    $student = Student::factory()->create();
    
    CourseEnrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'is_completed' => true,
        'progress_percentage' => 100,
    ]);

    // Generate first certificate
    $this->certificateService->generateCertificate($student, $course);
    
    // Try to generate second certificate
    $isEligible = $this->certificateService->isEligibleForCertificate($student, $course);
    
    expect($isEligible)->toBeFalse();
});

test('bulk certificate generation works', function () {
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $teacher->id]);
    
    // Create 3 completed enrollments
    $students = Student::factory()->count(3)->create();
    
    foreach ($students as $student) {
        CourseEnrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'is_completed' => true,
            'progress_percentage' => 100,
        ]);
    }

    // Generate certificates in bulk
    $generated = $this->certificateService->autoGenerateCertificates($course);

    expect($generated)->toBe(3);
    expect(Certificate::count())->toBe(3);
});

test('certificate pdf generation works', function () {
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $teacher->id]);
    $student = Student::factory()->create();
    
    CourseEnrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'is_completed' => true,
        'progress_percentage' => 100,
    ]);

    $certificate = $this->certificateService->generateCertificate($student, $course);

    expect($certificate->certificate_path)->not->toBeNull();
    expect($certificate->certificate_path)->toContain('certificates/');
    expect(\Storage::disk('public')->exists($certificate->certificate_path))->toBeTrue();
});

test('certificate can be regenerated if pdf is missing', function () {
    $certificate = Certificate::factory()->create(['certificate_path' => null]);
    
    $regenerated = $this->certificateService->regenerateCertificatePDF($certificate);
    
    expect($regenerated->certificate_path)->not->toBeNull();
    expect(\Storage::disk('public')->exists($regenerated->certificate_path))->toBeTrue();
});