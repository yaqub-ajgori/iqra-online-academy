<?php

use App\Models\{User, Student, Teacher, Course, CourseCategory, CourseModule, CourseLesson};
use App\Filament\Resources\Courses\CourseResource;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create admin user
    $this->admin = User::factory()->create(['email' => 'admin@test.com']);
    
    // Create test data
    $this->teacher = Teacher::factory()->create();
    $this->category = CourseCategory::factory()->create();
});

test('admin can view courses list page', function () {
    // Create some courses
    Course::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)->get('/admin/courses');

    expect($response->status())->toBe(200);
});

test('admin can view course creation page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/courses/create');

    expect($response->status())->toBe(200);
});

test('admin can create new course', function () {
    $courseData = [
        'title' => 'Test Course',
        'slug' => 'test-course',
        'full_description' => 'This is a test course description.',
        'category_id' => $this->category->id,
        'instructor_id' => $this->teacher->id,
        'level' => 'beginner',
        'price' => 1000,
        'currency' => 'BDT',
        'duration_in_minutes' => 600,
        'status' => 'published',
        'is_featured' => false,
        'is_free' => false,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/courses', $courseData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('courses', [
        'title' => 'Test Course',
        'slug' => 'test-course',
    ]);
});

test('admin can view course edit page', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
    ]);

    $response = $this->actingAs($this->admin)->get("/admin/courses/{$course->id}/edit");

    expect($response->status())->toBe(200);
});

test('admin can update course', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
        'title' => 'Original Title',
    ]);

    $updateData = [
        'title' => 'Updated Course Title',
        'slug' => 'updated-course-title',
        'full_description' => $course->full_description,
        'category_id' => $this->category->id,
        'instructor_id' => $this->teacher->id,
        'level' => 'intermediate',
        'price' => 1500,
        'currency' => 'BDT',
        'duration_in_minutes' => 720,
        'status' => 'published',
        'is_featured' => true,
        'is_free' => false,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/courses/{$course->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'Updated Course Title',
        'level' => 'intermediate',
        'is_featured' => true,
    ]);
});

test('admin can delete course', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
    ]);

    $response = $this->actingAs($this->admin)->delete("/admin/courses/{$course->id}");

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseMissing('courses', ['id' => $course->id]);
});

test('course validation works correctly', function () {
    $invalidData = [
        'title' => '', // Required field empty
        'price' => -100, // Invalid price
        'level' => 'invalid_level', // Invalid enum value
    ];

    $response = $this->actingAs($this->admin)->post('/admin/courses', $invalidData);

    expect($response->status())->toBeIn([422, 302]); // Validation error or redirect back
});

test('admin can manage course modules', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
    ]);

    // Create module
    $moduleData = [
        'course_id' => $course->id,
        'title' => 'Test Module',
        'description' => 'Test module description',
        'sort_order' => 1,
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/course-modules', $moduleData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('course_modules', [
        'course_id' => $course->id,
        'title' => 'Test Module',
    ]);
});

test('admin can manage course lessons', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
    ]);
    
    $module = CourseModule::factory()->create(['course_id' => $course->id]);

    // Create lesson
    $lessonData = [
        'course_id' => $course->id,
        'module_id' => $module->id,
        'title' => 'Test Lesson',
        'content' => 'Test lesson content',
        'type' => 'text',
        'duration_minutes' => 30,
        'sort_order' => 1,
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/course-lessons', $lessonData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('course_lessons', [
        'course_id' => $course->id,
        'module_id' => $module->id,
        'title' => 'Test Lesson',
    ]);
});

test('course status workflow works correctly', function () {
    $course = Course::factory()->create([
        'instructor_id' => $this->teacher->id,
        'category_id' => $this->category->id,
        'status' => 'draft',
    ]);

    // Update to published
    $response = $this->actingAs($this->admin)->put("/admin/courses/{$course->id}", [
        'title' => $course->title,
        'slug' => $course->slug,
        'full_description' => $course->full_description,
        'category_id' => $this->category->id,
        'instructor_id' => $this->teacher->id,
        'level' => $course->level,
        'price' => $course->price,
        'currency' => $course->currency,
        'duration_in_minutes' => $course->duration_in_minutes,
        'status' => 'published',
        'is_featured' => $course->is_featured,
        'is_free' => $course->is_free,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'status' => 'published',
    ]);
});