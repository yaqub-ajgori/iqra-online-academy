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
            $table->longText('full_description')->nullable();
            
            // Course Details
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->onDelete('restrict');
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            
            // Instructor
            $table->foreignId('instructor_id')->nullable()->constrained('teachers')->onDelete('restrict');
            
            // Course Content
            $table->string('thumbnail_image', 500)->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->timestamp('discount_expires_at')->nullable();
            
            // Course Metrics
            $table->integer('duration_in_minutes')->nullable();
            
            // Course Status
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false);
            
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
            $table->index('price', 'idx_courses_price');
            
            // Fulltext index (MySQL only)
            if (config('database.default') === 'mysql') {
                $table->fullText(['title', 'full_description'], 'ft_courses_title_full_description');
            }
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