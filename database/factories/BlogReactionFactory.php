<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\BlogReaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogReaction>
 */
class BlogReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_post_id' => BlogPost::factory(),
            'user_id' => User::factory(),
            'session_id' => null, // For registered users
            'type' => fake()->randomElement(['like', 'helpful']),
        ];
    }

    /**
     * Create a guest reaction.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'session_id' => fake()->uuid(),
        ]);
    }

    /**
     * Create a like reaction.
     */
    public function like(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'like',
        ]);
    }

    /**
     * Create a helpful reaction.
     */
    public function helpful(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'helpful',
        ]);
    }
}