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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->string('phone', 20)->nullable();
            
            // Qualifications
            $table->string('highest_qualification')->nullable();
            $table->json('islamic_qualifications')->nullable(); // Array of Islamic degrees/certifications
            $table->json('secular_qualifications')->nullable(); // Array of secular degrees
            $table->json('specializations')->nullable(); // Areas of expertise
            
            // Experience
            $table->integer('teaching_experience_years')->default(0);
            $table->integer('islamic_teaching_experience_years')->default(0);
            $table->json('previous_institutions')->nullable(); // Teaching history
            
            // Profile
            $table->text('bio')->nullable();
            $table->string('profile_picture', 500)->nullable();
            $table->json('languages_spoken')->nullable(); // Languages teacher can teach in
            
            // Teaching Preferences
            $table->json('preferred_subjects')->nullable(); // Subjects they prefer to teach
            $table->json('availability_schedule')->nullable(); // Weekly availability
            $table->decimal('hourly_rate', 8, 2)->nullable(); // If applicable
            
            // Verification & Status
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable(); // Certification docs
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Performance Metrics
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_students_taught')->default(0);
            $table->integer('total_courses_taught')->default(0);
            
            // Account Status
            $table->boolean('is_active')->default(true);
            $table->boolean('can_create_courses')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id', 'idx_teachers_user_id');
            $table->index('is_verified', 'idx_teachers_verified');
            $table->index('is_active', 'idx_teachers_active');
            $table->index('average_rating', 'idx_teachers_rating');
            $table->index('verification_status', 'idx_teachers_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
}; 