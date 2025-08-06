<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // For easy access
            
            // Review Content
            $table->text('review_text');
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->string('title')->nullable(); // Optional review title
            
            // Review Status & Moderation
            $table->enum('status', ['pending', 'approved', 'rejected', 'hidden'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('moderation_notes')->nullable();
            
            // Review Metadata
            $table->boolean('is_featured')->default(false); // For homepage testimonials
            $table->boolean('is_verified_purchase')->default(false); // Only enrolled students
            $table->integer('helpful_count')->default(0); // How many found it helpful
            $table->integer('reported_count')->default(0); // Spam/abuse reports
            
            // Additional Info
            $table->json('metadata')->nullable(); // Extra data like completion status
            $table->timestamps();
            
            // Indexes
            $table->index('course_id');
            $table->index('student_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('rating');
            $table->index('is_featured');
            $table->index(['course_id', 'status']);
            $table->index(['course_id', 'rating']);
            $table->index(['status', 'is_featured']);
            $table->index('created_at');
            
            // Ensure one review per student per course
            $table->unique(['course_id', 'student_id'], 'unique_course_student_review');
        });
        
        // Helpful votes table
        Schema::create('course_review_helpful_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('course_reviews')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable(); // For guest votes
            $table->string('session_id')->nullable(); // For guest votes
            $table->timestamps();
            
            // Prevent duplicate votes
            $table->unique(['review_id', 'user_id'], 'unique_user_review_vote');
            $table->index(['review_id']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_review_helpful_votes');
        Schema::dropIfExists('course_reviews');
    }
};