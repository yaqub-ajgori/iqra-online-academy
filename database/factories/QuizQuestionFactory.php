<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        return [
            'quiz_id' => null, // Set in seeder
            'question' => $this->faker->sentence(8),
            'correct_answer' => $this->faker->sentence(6),
            'options' => json_encode([$this->faker->word, $this->faker->word, $this->faker->word]),
        ];
    }
}
