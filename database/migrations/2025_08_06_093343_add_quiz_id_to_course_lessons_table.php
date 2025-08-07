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
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->foreignId('quiz_id')->nullable()->constrained('quizzes')->onDelete('set null');
            $table->index('quiz_id', 'idx_lessons_quiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropIndex('idx_lessons_quiz');
            $table->dropColumn('quiz_id');
        });
    }
};
