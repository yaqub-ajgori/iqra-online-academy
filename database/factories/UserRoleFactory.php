<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'role_type' => fake()->randomElement(['student', 'teacher', 'admin']),
        ];
    }

    /**
     * Create an admin role.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_type' => 'admin',
        ]);
    }

    /**
     * Create a teacher role.
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_type' => 'teacher',
        ]);
    }

    /**
     * Create a student role.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_type' => 'student',
        ]);
    }
}