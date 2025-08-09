<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'course_id' => null, // Set in seeder
            'description' => $this->faker->sentence(8),
            'status' => 'active',
        ];
    }
}
