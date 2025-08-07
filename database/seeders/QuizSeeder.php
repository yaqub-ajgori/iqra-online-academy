<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing courses or create one if none exist
        $courses = Course::limit(3)->get();
        
        if ($courses->isEmpty()) {
            $courses = Course::factory(3)->create();
        }

        foreach ($courses as $course) {
            // Create 2-3 quizzes per course
            $quizCount = fake()->numberBetween(2, 3);
            
            for ($i = 0; $i < $quizCount; $i++) {
                $title = $this->getQuizTitle($i) . ' - ' . $course->title;
                $quiz = Quiz::create([
                    'course_id' => $course->id,
                    'lesson_id' => null,
                    'title' => $title,
                    'slug' => Str::slug($title . '-' . time() . '-' . $i),
                    'description' => $this->getQuizDescription($i),
                    'type' => $i === 0 ? 'quiz' : ($i === $quizCount - 1 ? 'exam' : 'assessment'),
                    'time_limit_minutes' => $i === $quizCount - 1 ? 120 : ($i === 0 ? 30 : 60),
                    'max_attempts' => $i === $quizCount - 1 ? 1 : 3,
                    'passing_score' => 70.00,
                    'randomize_questions' => $i > 0,
                    'show_results_immediately' => $i !== $quizCount - 1,
                    'allow_review' => true,
                    'is_active' => true,
                    'sort_order' => $i,
                ]);

                // Create questions for each quiz
                $this->createQuestionsForQuiz($quiz);
            }

            // Create lesson-specific quiz
            $lesson = $course->modules()->first()?->lessons()->first();
            if ($lesson) {
                $lessonQuizTitle = 'পাঠ মূল্যায়ন: ' . $lesson->title;
                $lessonQuiz = Quiz::create([
                    'course_id' => $course->id,
                    'lesson_id' => $lesson->id,
                    'title' => $lessonQuizTitle,
                    'slug' => Str::slug($lessonQuizTitle . '-lesson-' . $lesson->id),
                    'description' => 'এই পাঠের বিষয়বস্তু সম্পর্কে আপনার জ্ঞান যাচাই করুন।',
                    'type' => 'quiz',
                    'time_limit_minutes' => 15,
                    'max_attempts' => 5,
                    'passing_score' => 60.00,
                    'randomize_questions' => false,
                    'show_results_immediately' => true,
                    'allow_review' => true,
                    'is_active' => true,
                    'sort_order' => 0,
                ]);

                $this->createQuestionsForQuiz($lessonQuiz, 5);
                
                // Update lesson to reference this quiz
                $lesson->update(['quiz_id' => $lessonQuiz->id]);
            }
        }

        // Create some quiz attempts for existing students
        $students = Student::limit(5)->get();
        $quizzes = Quiz::where('is_active', true)->limit(10)->get();

        foreach ($students as $student) {
            foreach ($quizzes->random(min(3, $quizzes->count())) as $quiz) {
                // Create 1-2 attempts per quiz
                $attemptCount = fake()->numberBetween(1, min(2, $quiz->max_attempts));
                
                for ($a = 0; $a < $attemptCount; $a++) {
                    $attempt = QuizAttempt::factory()
                        ->for($quiz)
                        ->for($student)
                        ->create();
                    
                    // Recalculate score based on actual quiz questions
                    if ($attempt->completed_at) {
                        $attempt->calculateScore();
                    }
                }
            }
        }
    }

    /**
     * Get quiz title based on index.
     */
    private function getQuizTitle(int $index): string
    {
        $titles = [
            'প্রাথমিক মূল্যায়ন',
            'মধ্যবর্তী পরীক্ষা',
            'চূড়ান্ত পরীক্ষা',
        ];

        return $titles[$index] ?? 'কুইজ ' . ($index + 1);
    }

    /**
     * Get quiz description based on index.
     */
    private function getQuizDescription(int $index): string
    {
        $descriptions = [
            'এই কুইজটি আপনার প্রাথমিক জ্ঞান যাচাই করবে। সকল প্রশ্নের উত্তর দিন এবং আপনার স্কোর দেখুন।',
            'মধ্যবর্তী মূল্যায়নে আপনার এখন পর্যন্ত শেখা বিষয়গুলো পরীক্ষা করা হবে।',
            'চূড়ান্ত পরীক্ষায় সম্পূর্ণ কোর্সের বিষয়বস্তু অন্তর্ভুক্ত থাকবে। সতর্কতার সাথে উত্তর দিন।',
        ];

        return $descriptions[$index] ?? 'এই কুইজটি সম্পূর্ণ করুন এবং আপনার জ্ঞান যাচাই করুন।';
    }

    /**
     * Create questions for a quiz.
     */
    private function createQuestionsForQuiz(Quiz $quiz, int $count = 10): void
    {
        // Create multiple choice questions
        for ($i = 0; $i < ceil($count * 0.5); $i++) {
            QuizQuestion::factory()
                ->multipleChoice()
                ->for($quiz)
                ->create([
                    'sort_order' => $i,
                ]);
        }

        // Create true/false questions
        for ($i = ceil($count * 0.5); $i < ceil($count * 0.8); $i++) {
            QuizQuestion::factory()
                ->trueFalse()
                ->for($quiz)
                ->create([
                    'sort_order' => $i,
                ]);
        }

        // Create short answer questions
        for ($i = ceil($count * 0.8); $i < $count; $i++) {
            QuizQuestion::factory()
                ->shortAnswer()
                ->for($quiz)
                ->create([
                    'sort_order' => $i,
                ]);
        }

        // Add one essay question for exams
        if ($quiz->type === 'exam') {
            QuizQuestion::factory()
                ->essay()
                ->for($quiz)
                ->create([
                    'sort_order' => $count,
                ]);
        }
    }
}