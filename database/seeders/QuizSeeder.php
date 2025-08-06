<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\QuizAttempt;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::published()->get();

        foreach ($courses as $course) {
            // Create 1-3 quizzes per course
            $quizCount = rand(1, 3);
            
            for ($i = 1; $i <= $quizCount; $i++) {
                $quizTypes = ['quiz', 'exam', 'assessment'];
                $quizTitles = [
                    'প্রাথমিক পর্যায়ের পরীক্ষা',
                    'মধ্যবর্তী মূল্যায়ন',
                    'চূড়ান্ত পরীক্ষা'
                ];

                $quiz = Quiz::create([
                    'course_id' => $course->id,
                    'lesson_id' => null, // Course level quiz
                    'title' => $quizTitles[($i - 1) % count($quizTitles)] . " - {$course->title}",
                    'description' => "এই পরীক্ষায় {$course->title} কোর্সের মূল বিষয়গুলি থেকে প্রশ্ন করা হবে।",
                    'type' => $quizTypes[($i - 1) % count($quizTypes)],
                    'time_limit_minutes' => [30, 45, 60][($i - 1) % 3],
                    'max_attempts' => [3, 2, 1][$i - 1],
                    'passing_score' => [60.00, 70.00, 75.00][$i - 1],
                    'show_results_immediately' => true,
                    'allow_review' => true,
                    'randomize_questions' => fake()->boolean(50),
                    'available_from' => now()->subDays(30),
                    'available_until' => now()->addMonths(6),
                    'is_active' => true,
                    'sort_order' => $i,
                ]);

                // Create questions for this quiz
                $this->createQuizQuestions($quiz);
            }
        }

        // Create some quiz attempts
        $this->createQuizAttempts();
    }

    private function createQuizQuestions(Quiz $quiz): void
    {
        $questionCount = rand(5, 15);
        
        $islamicQuestions = [
            // Multiple Choice Questions
            [
                'type' => 'multiple_choice',
                'question' => 'পবিত্র কুরআনে কতটি সূরা রয়েছে?',
                'options' => ['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি'],
                'correct' => ['১১৪টি'],
                'explanation' => 'পবিত্র কুরআনে মোট ১১৪টি সূরা রয়েছে।'
            ],
            [
                'type' => 'multiple_choice',
                'question' => 'হযরত মুহাম্মদ (সা.) কোন শহরে জন্মগ্রহণ করেন?',
                'options' => ['মক্কা', 'মদীনা', 'তায়েফ', 'জেদ্দা'],
                'correct' => ['মক্কা'],
                'explanation' => 'রাসূল (সা.) মক্কা শরীফে জন্মগ্রহণ করেন।'
            ],
            [
                'type' => 'multiple_choice',
                'question' => 'ইসলামের কতটি মূল স্তম্ভ রয়েছে?',
                'options' => ['৩টি', '৪টি', '৫টি', '৬টি'],
                'correct' => ['৫টি'],
                'explanation' => 'ইসলামের পাঁচটি স্তম্ভ: কালেমা, নামাজ, রোজা, হজ ও জাকাত।'
            ],
            [
                'type' => 'multiple_choice',
                'question' => 'রমজান মাসে কোন ইবাদত ফরজ?',
                'options' => ['হজ', 'রোজা', 'জাকাত', 'কুরবানী'],
                'correct' => ['রোজা'],
                'explanation' => 'রমজান মাসে রোজা রাখা প্রতিটি প্রাপ্তবয়স্ক মুসলমানের জন্য ফরজ।'
            ],
            
            // True/False Questions
            [
                'type' => 'true_false',
                'question' => 'ইসলামে পাঁচ ওয়াক্ত নামাজ ফরজ।',
                'options' => ['সত্য', 'মিথ্যা'],
                'correct' => ['সত্য'],
                'explanation' => 'দৈনিক পাঁচ ওয়াক্ত নামাজ প্রতিটি মুসলমানের জন্য ফরজ।'
            ],
            [
                'type' => 'true_false',
                'question' => 'জাকাত শুধুমাত্র রমজান মাসে দিতে হয়।',
                'options' => ['সত্য', 'মিথ্যা'],
                'correct' => ['মিথ্যা'],
                'explanation' => 'জাকাত বছরের যেকোনো সময় দেওয়া যায়, শুধু রমজানে নয়।'
            ],
            [
                'type' => 'true_false',
                'question' => 'কুরআন আরবি ভাষায় নাজিল হয়েছে।',
                'options' => ['সত্য', 'মিথ্যা'],
                'correct' => ['সত্য'],
                'explanation' => 'পবিত্র কুরআন আরবি ভাষায় নাজিল হয়েছে।'
            ],
            
            // Short Answer Questions
            [
                'type' => 'short_answer',
                'question' => 'ইসলামের পাঁচটি স্তম্ভের নাম লিখুন।',
                'options' => [],
                'correct' => ['কালেমা, নামাজ, রোজা, হজ, জাকাত', 'শাহাদাহ, সালাহ, সিয়াম, হজ, জাকাত'],
                'explanation' => 'ইসলামের পাঁচটি স্তম্ভ হলো: কালেমা (শাহাদাহ), নামাজ (সালাহ), রোজা (সিয়াম), হজ ও জাকাত।'
            ],
            [
                'type' => 'short_answer',
                'question' => 'পবিত্র কুরআনের প্রথম সূরার নাম কী?',
                'options' => [],
                'correct' => ['সূরা ফাতিহা', 'আল-ফাতিহা'],
                'explanation' => 'পবিত্র কুরআনের প্রথম সূরা হলো সূরা ফাতিহা।'
            ]
        ];

        for ($i = 1; $i <= $questionCount; $i++) {
            $questionData = $islamicQuestions[($i - 1) % count($islamicQuestions)];
            
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'],
                'correct_answers' => $questionData['correct'],
                'explanation' => $questionData['explanation'],
                'points' => $questionData['type'] === 'short_answer' ? 5 : ($questionData['type'] === 'multiple_choice' ? 2 : 1),
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
    }

    private function createQuizAttempts(): void
    {
        $students = Student::take(10)->get();
        $quizzes = Quiz::take(5)->get();

        foreach ($students as $student) {
            foreach ($quizzes as $quiz) {
                // Skip if student is not enrolled in the course
                if (!$student->enrollments()->where('course_id', $quiz->course_id)->exists()) {
                    continue;
                }

                // 70% chance to attempt each quiz
                if (fake()->boolean(70)) {
                    $attemptCount = rand(1, $quiz->max_attempts);
                    
                    for ($i = 1; $i <= $attemptCount; $i++) {
                        $startedAt = \Carbon\Carbon::instance(fake()->dateTimeBetween('-1 month', 'now'));
                        $completedAt = $startedAt->copy()->addMinutes(rand(10, $quiz->time_limit_minutes ?? 30));
                        
                        // Generate random answers
                        $questions = $quiz->questions()->active()->get();
                        $answers = [];
                        
                        foreach ($questions as $question) {
                            if ($question->type === 'multiple_choice') {
                                $answers[$question->id] = fake()->randomElement($question->options);
                            } elseif ($question->type === 'true_false') {
                                $answers[$question->id] = fake()->randomElement(['সত্য', 'মিথ্যা']);
                            } else {
                                $answers[$question->id] = fake()->sentence(5);
                            }
                        }
                        
                        $attempt = QuizAttempt::create([
                            'quiz_id' => $quiz->id,
                            'student_id' => $student->id,
                            'answers' => $answers,
                            'started_at' => $startedAt,
                            'completed_at' => $completedAt,
                            'time_taken_seconds' => $completedAt->diffInSeconds($startedAt),
                        ]);

                        // Calculate score (simplified)
                        $attempt->calculateScore();
                    }
                }
            }
        }
    }
}