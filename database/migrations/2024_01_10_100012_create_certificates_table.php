<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Certificates table
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique(); // Unique certificate ID
            $table->string('student_name');
            $table->string('course_title');
            $table->text('course_description')->nullable();
            $table->json('instructors'); // Array of instructor names
            $table->decimal('final_score', 5, 2)->nullable();
            $table->date('completion_date');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            
            // Certificate file paths
            $table->string('certificate_path')->nullable(); // Generated PDF path
            $table->string('template_used')->default('default');
            
            // Verification
            $table->string('verification_code')->unique(); // Public verification code
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable(); // Additional certificate data
            $table->timestamps();
            
            // Indexes
            $table->index('student_id');
            $table->index('course_id');
            $table->index('certificate_number');
            $table->index('verification_code');
            $table->index(['student_id', 'course_id']);
        });
        
        // Certificate Templates table
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('template_path'); // Path to template file
            $table->json('design_settings'); // Colors, fonts, layout settings
            $table->json('text_positions'); // Position of dynamic text elements
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('certificates');
    }
};