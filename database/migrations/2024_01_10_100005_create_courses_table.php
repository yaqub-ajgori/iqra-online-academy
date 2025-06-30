<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('full_description')->nullable();
            
            // Course Details
            $table->foreignId('category_id')->constrained('course_categories')->onDelete('restrict');
            $table->foreignId('subcategory_id')->nullable()->constrained('course_categories')->onDelete('set null');
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->enum('language', ['bengali', 'arabic', 'english'])->default('bengali');
            
            // Instructor
            $table->foreignId('instructor_id')->constrained('teachers')->onDelete('restrict');
            $table->json('co_instructors')->nullable(); // Additional instructors
            
            // Course Content
            $table->string('thumbnail_image', 500)->nullable();
            $table->string('preview_video_url', 500)->nullable();
            $table->json('course_outline')->nullable(); // Course structure
            $table->json('learning_outcomes')->nullable(); // What students will learn
            $table->json('requirements')->nullable(); // Prerequisites
            
            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->timestamp('discount_expires_at')->nullable();
            
            // Course Metrics
            $table->integer('duration_hours')->nullable(); // Estimated completion time
            $table->integer('total_lessons')->default(0);
            $table->integer('total_quizzes')->default(0);
            $table->integer('total_assignments')->default(0);
            
            // Enrollment
            $table->integer('max_students')->nullable(); // Enrollment limit
            $table->integer('current_students')->default(0);
            $table->timestamp('enrollment_starts_at')->nullable();
            $table->timestamp('enrollment_ends_at')->nullable();
            
            // Course Status
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false);
            
            // Ratings
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('slug', 'idx_courses_slug');
            $table->index('category_id', 'idx_courses_category');
            $table->index('instructor_id', 'idx_courses_instructor');
            $table->index('status', 'idx_courses_status');
            $table->index('level', 'idx_courses_level');
            $table->index('is_featured', 'idx_courses_featured');
            $table->index('average_rating', 'idx_courses_rating');
            $table->index('price', 'idx_courses_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
}; 