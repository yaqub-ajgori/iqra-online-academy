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
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->text('description')->nullable(); // Short description
            $table->enum('type', ['text', 'video', 'pdf'])->default('text');
            
            // Video Content - for YouTube/Vimeo links
            $table->string('video_url', 500)->nullable();
            
            // File Content - for PDF uploads
            $table->string('file_path')->nullable(); // Simplified from primary_file_path
            
            // Access Control
            $table->boolean('is_preview')->default(false); // Can be viewed without enrollment
            
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('module_id', 'idx_lessons_module');
            $table->index('course_id', 'idx_lessons_course');
            $table->index('type', 'idx_lessons_type');
            $table->index('sort_order', 'idx_lessons_order');
            $table->index('is_preview', 'idx_lessons_preview');
            $table->index(['module_id', 'sort_order'], 'idx_lessons_module_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
}; 