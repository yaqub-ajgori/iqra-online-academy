<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizTestSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first course for testing
        $course = Course::first();
        
        if (!$course) {
            $this->command->warn('No courses found. Please create a course first.');
            return;
        }

        // Create a test quiz
        $quiz = Quiz::create([
            'title' => 'ইসলামিক মৌলিক জ্ঞান কুইজ',
            'description' => 'ইসলামের মৌলিক বিষয়গুলো নিয়ে একটি সহজ কুইজ',
            'course_id' => $course->id,
            'passing_score' => 70,
            'time_limit_minutes' => 10,
            'max_attempts' => 3,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        // Add some test questions
        $questions = [
            [
                'question' => 'ইসলামের পঞ্চস্তম্ভ কয়টি?',
                'type' => 'multiple_choice',
                'options' => ['৪টি', '৫টি', '৬টি', '৭টি'],
                'correct_answer' => '1', // ৫টি (index 1)
                'points' => 1,
                'sort_order' => 1,
            ],
            [
                'question' => 'কুরআন মাজীদ আল্লাহর পক্ষ থেকে নাযিলকৃত।',
                'type' => 'true_false',
                'options' => null,
                'correct_answer' => 'সত্য',
                'points' => 1,
                'sort_order' => 2,
            ],
            [
                'question' => 'ইসলামের প্রথম খলিফার নাম কী?',
                'type' => 'short_answer',
                'options' => null,
                'correct_answer' => 'আবু বকর',
                'points' => 1,
                'sort_order' => 3,
            ],
            [
                'question' => 'হজ্জ কোন মাসে পালন করা হয়?',
                'type' => 'multiple_choice',
                'options' => ['রমজান', 'শাওয়াল', 'জিলহজ্জ', 'মুহাররম'],
                'correct_answer' => '2', // জিলহজ্জ (index 2)
                'points' => 1,
                'sort_order' => 4,
            ],
            [
                'question' => 'নামাজ দিনে ৫ বার ফরজ।',
                'type' => 'true_false',
                'options' => null,
                'correct_answer' => 'সত্য',
                'points' => 1,
                'sort_order' => 5,
            ],
        ];

        foreach ($questions as $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                ...$questionData
            ]);
        }

        $this->command->info("Test quiz created successfully with {$quiz->questions()->count()} questions!");
        $this->command->info("Course: {$course->title}");
        $this->command->info("Quiz: {$quiz->title}");
    }
}