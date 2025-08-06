<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bangladeshiNames = [
            'আবদুল্লাহ রহমান', 'ফাতিমা খাতুন', 'মুহাম্মদ ইব্রাহিম', 'আয়শা বেগম',
            'আবু বকর সিদ্দিক', 'খাদিজা আক্তার', 'উমর ফারুক', 'জয়নব খাতুন',
            'আলী হাসান', 'রুকাইয়া পারভীন', 'উসমান গনি', 'সুমাইয়া খাতুন',
            'মুসা করিম', 'মরিয়ম আক্তার', 'ইউসুফ আলী', 'হাওয়া বেগম',
            'ইসমাইল হক', 'আমিনা খাতুন', 'দাউদ মিয়া', 'সাফিয়া পারভীন'
        ];

        return [
            'name' => $this->faker->randomElement($bangladeshiNames),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'অ্যাডমিন ইউজার',
            'email' => 'admin@iqra.academy',
        ]);
    }
}