<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    public function definition(): array
    {
        $questionTypes = ['multiple_choice', 'true_false', 'short_answer'];
        $type = $this->faker->randomElement($questionTypes);
        
        $islamicQuestions = [
            'multiple_choice' => [
                'question' => 'পবিত্র কুরআনে কতটি সূরা রয়েছে?',
                'options' => ['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি'],
                'correct' => ['১১৪টি']
            ],
            'true_false' => [
                'question' => 'ইসলামে পাঁচ ওয়াক্ত নামাজ ফরজ।',
                'options' => ['সত্য', 'মিথ্যা'],
                'correct' => ['সত্য']
            ],
            'short_answer' => [
                'question' => 'ইসলামের পাঁচটি স্তম্ভের নাম লিখুন।',
                'options' => [],
                'correct' => ['কালেমা, নামাজ, রোজা, হজ, জাকাত']
            ]
        ];

        $multipleChoiceQuestions = [
            ['question' => 'পবিত্র কুরআনে কতটি সূরা রয়েছে?', 'options' => ['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি'], 'correct' => ['১১৪টি']],
            ['question' => 'হযরত মুহাম্মদ (সা.) কোন শহরে জন্মগ্রহণ করেন?', 'options' => ['মক্কা', 'মদীনা', 'তায়েফ', 'জেদ্দা'], 'correct' => ['মক্কা']],
            ['question' => 'ইসলামের কতটি মূল স্তম্ভ রয়েছে?', 'options' => ['৩টি', '৪টি', '৫টি', '৬টি'], 'correct' => ['৫টি']],
            ['question' => 'রমজান মাসে কোন ইবাদত ফরজ?', 'options' => ['হজ', 'রোজা', 'জাকাত', 'কুরবানী'], 'correct' => ['রোজা']],
            ['question' => 'কাবা শরীফ কোন শহরে অবস্থিত?', 'options' => ['মক্কা', 'মদীনা', 'জেরুজালেম', 'কায়রো'], 'correct' => ['মক্কা']],
        ];

        $trueFalseQuestions = [
            ['question' => 'ইসলামে পাঁচ ওয়াক্ত নামাজ ফরজ।', 'correct' => ['সত্য']],
            ['question' => 'জাকাত শুধুমাত্র রমজান মাসে দিতে হয়।', 'correct' => ['মিথ্যা']],
            ['question' => 'হজ প্রতিবছর আদায় করা ফরজ।', 'correct' => ['মিথ্যা']],
            ['question' => 'কুরআন আরবি ভাষায় নাজিল হয়েছে।', 'correct' => ['সত্য']],
            ['question' => 'ইসলামে সুদ হারাম।', 'correct' => ['সত্য']],
        ];

        $shortAnswerQuestions = [
            ['question' => 'ইসলামের পাঁচটি স্তম্ভের নাম লিখুন।', 'correct' => ['কালেমা, নামাজ, রোজা, হজ, জাকাত']],
            ['question' => 'পবিত্র কুরআনের প্রথম সূরার নাম কী?', 'correct' => ['সূরা ফাতিহা']],
            ['question' => 'রাসূল (সা.) এর স্ত্রীদের কী বলা হয়?', 'correct' => ['উম্মুল মুমিনীন']],
            ['question' => 'ইসলামী ক্যালেন্ডারের প্রথম মাসের নাম কী?', 'correct' => ['মুহাররম']],
            ['question' => 'কুরআনের সবচেয়ে ছোট সূরার নাম কী?', 'correct' => ['সূরা কাউসার']],
        ];

        if ($type === 'multiple_choice') {
            $question = $this->faker->randomElement($multipleChoiceQuestions);
            return [
                'quiz_id' => Quiz::factory(),
                'question' => $question['question'],
                'type' => $type,
                'options' => json_encode($question['options']),
                'correct_answers' => json_encode($question['correct']),
                'explanation' => $this->faker->optional()->sentence(),
                'points' => $this->faker->numberBetween(1, 5),
                'sort_order' => $this->faker->numberBetween(1, 50),
                'is_active' => true,
            ];
        } elseif ($type === 'true_false') {
            $question = $this->faker->randomElement($trueFalseQuestions);
            return [
                'quiz_id' => Quiz::factory(),
                'question' => $question['question'],
                'type' => $type,
                'options' => json_encode(['সত্য', 'মিথ্যা']),
                'correct_answers' => json_encode($question['correct']),
                'explanation' => $this->faker->optional()->sentence(),
                'points' => $this->faker->numberBetween(1, 3),
                'sort_order' => $this->faker->numberBetween(1, 50),
                'is_active' => true,
            ];
        } else { // short_answer
            $question = $this->faker->randomElement($shortAnswerQuestions);
            return [
                'quiz_id' => Quiz::factory(),
                'question' => $question['question'],
                'type' => $type,
                'options' => json_encode([]),
                'correct_answers' => json_encode($question['correct']),
                'explanation' => $this->faker->optional()->sentence(),
                'points' => $this->faker->numberBetween(3, 10),
                'sort_order' => $this->faker->numberBetween(1, 50),
                'is_active' => true,
            ];
        }
    }

    public function multipleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'multiple_choice',
            'options' => json_encode(['বিকল্প ১', 'বিকল্প ২', 'বিকল্প ৩', 'বিকল্প ৪']),
            'correct_answers' => json_encode(['বিকল্প ১']),
        ]);
    }

    public function trueFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'true_false',
            'options' => json_encode(['সত্য', 'মিথ্যা']),
            'correct_answers' => json_encode(['সত্য']),
        ]);
    }

    public function shortAnswer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'short_answer',
            'options' => json_encode([]),
            'correct_answers' => json_encode(['নমুনা উত্তর']),
        ]);
    }
}