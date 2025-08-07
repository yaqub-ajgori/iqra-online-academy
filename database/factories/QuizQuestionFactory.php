<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['multiple_choice', 'true_false', 'short_answer']);
        
        $questionData = [
            'quiz_id' => Quiz::factory(),
            'question' => $this->getRandomQuestion($type),
            'type' => $type,
            'points' => fake()->randomElement([1, 2, 3, 5]),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'explanation' => fake()->optional(0.7)->sentence(8),
        ];

        switch ($type) {
            case 'multiple_choice':
                $questionSet = $this->getMultipleChoiceQuestion();
                $questionData['question'] = $questionSet['question'];
                $questionData['options'] = $questionSet['options'];
                $questionData['correct_answers'] = [$questionSet['correct']];
                break;
                
            case 'true_false':
                $questionData['options'] = null;
                $questionData['correct_answers'] = [fake()->randomElement(['সত্য', 'মিথ্যা'])];
                break;
                
            case 'short_answer':
                $questionData['options'] = null;
                $questionData['correct_answers'] = ['আল্লাহ']; // Example answer
                break;
                
            case 'essay':
                $questionData['options'] = null;
                $questionData['correct_answers'] = [];
                $questionData['points'] = fake()->randomElement([5, 10, 15]);
                break;
        }

        return $questionData;
    }

    private function getRandomQuestion($type): string
    {
        $questions = [
            'multiple_choice' => [
                'কুরআন মাজীদে কতটি সূরা রয়েছে?',
                'ইসলামের পাঁচটি স্তম্ভের মধ্যে প্রথমটি কী?',
                'হযরত মুহাম্মদ (সা.) কোন বছর জন্মগ্রহণ করেন?',
                'কাবা শরীফ কোথায় অবস্থিত?',
                'নামাজে কতবার সেজদা করতে হয়?',
            ],
            'true_false' => [
                'রমজান মাস হিজরি বর্ষের নবম মাস।',
                'যাকাত সকল মুসলমানের উপর ফরজ।',
                'জুমআর নামাজ শুধু পুরুষদের জন্য ফরজ।',
                'কুরআনের প্রথম সূরা হল সূরা ফাতিহা।',
            ],
            'short_answer' => [
                'ইসলামের প্রথম খলিফার নাম কী?',
                'কে সর্বপ্রথম ইসলাম গ্রহণ করেন?',
                'ইসলামের পবিত্র গ্রন্থের নাম কী?',
            ],
        ];

        return fake()->randomElement($questions[$type] ?? $questions['multiple_choice']);
    }

    private function getMultipleChoiceQuestion(): array
    {
        $sets = [
            [
                'question' => 'কুরআন মাজীদে কতটি সূরা রয়েছে?',
                'options' => ['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি'],
                'correct' => 1 // 114টি
            ],
            [
                'question' => 'ইসলামের পাঁচটি স্তম্ভের মধ্যে প্রথমটি কী?',
                'options' => ['নামাজ', 'রোজা', 'কালেমা', 'যাকাত'],
                'correct' => 2 // কালেমা
            ],
            [
                'question' => 'হযরত মুহাম্মদ (সা.) কোন বছর জন্মগ্রহণ করেন?',
                'options' => ['৫৬৮ খ্রিস্টাব্দ', '৫৭০ খ্রিস্টাব্দ', '৫৭২ খ্রিস্টাব্দ', '৫৭৫ খ্রিস্টাব্দ'],
                'correct' => 1
            ]
        ];

        return fake()->randomElement($sets);
    }

    /**
     * Create a multiple choice question.
     */
    public function multipleChoice(): static
    {
        return $this->state(function (array $attributes) {
            $questionSet = $this->getMultipleChoiceQuestion();
            
            return [
                'question' => $questionSet['question'],
                'type' => 'multiple_choice',
                'options' => $questionSet['options'],
                'correct_answers' => [$questionSet['correct']],
            ];
        });
    }

    /**
     * Create a true/false question.
     */
    public function trueFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'true_false',
            'question' => fake()->randomElement([
                'রমজান মাস হিজরি বর্ষের নবম মাস।',
                'যাকাত সকল মুসলমানের উপর ফরজ।',
                'জুমআর নামাজ শুধু পুরুষদের জন্য ফরজ।',
            ]),
            'options' => null,
            'correct_answers' => [fake()->randomElement(['সত্য', 'মিথ্যা'])],
        ]);
    }

    /**
     * Create a short answer question.
     */
    public function shortAnswer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'short_answer',
            'question' => fake()->randomElement([
                'ইসলামের প্রথম খলিফার নাম কী?',
                'কে সর্বপ্রথম ইসলাম গ্রহণ করেন?',
            ]),
            'options' => null,
            'correct_answers' => ['আবু বকর'], // Example answer
        ]);
    }

    /**
     * Create an essay question.
     */
    public function essay(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'essay',
            'question' => 'ইসলামের পাঁচটি স্তম্ভের গুরুত্ব বিস্তারিত বর্ণনা করুন।',
            'options' => null,
            'correct_answers' => [], // Essays require manual grading
            'points' => fake()->randomElement([5, 10, 15, 20]),
        ]);
    }
}