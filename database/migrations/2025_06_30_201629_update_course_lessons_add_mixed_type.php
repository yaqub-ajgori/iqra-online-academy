<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the enum column
        DB::statement("ALTER TABLE course_lessons MODIFY COLUMN lesson_type ENUM('video', 'text', 'quiz', 'assignment', 'live', 'mixed') DEFAULT 'video'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE course_lessons MODIFY COLUMN lesson_type ENUM('video', 'text', 'quiz', 'assignment', 'live') DEFAULT 'video'");
    }
};
