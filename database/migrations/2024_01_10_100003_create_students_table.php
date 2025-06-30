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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Address Information
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('country', 100)->default('Bangladesh');
            $table->string('postal_code', 20)->nullable();
            
            // Islamic Education Background
            $table->enum('islamic_knowledge_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->text('previous_islamic_education')->nullable();
            $table->enum('memorization_status', ['none', 'partial', 'hafez'])->default('none');
            $table->enum('arabic_proficiency', ['none', 'basic', 'intermediate', 'advanced'])->default('none');
            
            // Learning Preferences
            $table->enum('preferred_language', ['bengali', 'arabic', 'english'])->default('bengali');
            $table->text('learning_goals')->nullable();
            $table->enum('study_schedule_preference', ['morning', 'afternoon', 'evening', 'flexible'])->default('flexible');
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relation', 100)->nullable();
            
            // Profile Information
            $table->string('profile_picture', 500)->nullable();
            $table->text('bio')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education_level')->nullable();
            
            // Account Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id', 'idx_students_user_id');
            $table->index('islamic_knowledge_level', 'idx_students_knowledge_level');
            $table->index('city', 'idx_students_city');
            $table->index('is_active', 'idx_students_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}; 