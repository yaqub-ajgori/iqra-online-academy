<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bangladeshiNames = [
            'আব্দুল করিম', 'ফাতেমা খাতুন', 'মোহাম্মদ আলি', 'রাহেলা বেগম', 'আহমদ হাসান',
            'খাদিজা আক্তার', 'ইউসুফ আলি', 'আয়েশা সিদ্দিকা', 'ওমর ফারুক', 'জয়নাব খাতুন'
        ];

        $districts = [
            'ঢাকা', 'চট্টগ্রাম', 'সিলেট', 'রাজশাহী', 'খুলনা', 'বরিশাল', 'রংপুর', 'ময়মনসিংহ',
            'গাজীপুর', 'নারায়ণগঞ্জ', 'কুমিল্লা', 'ফেনী', 'নোয়াখালী'
        ];

        return [
            'user_id' => User::factory(),
            'full_name' => fake()->randomElement($bangladeshiNames),
            'phone' => '01' . fake()->numberBetween(3, 9) . fake()->numerify('########'),
            'date_of_birth' => fake()->dateTimeBetween('-50 years', '-15 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'district' => fake()->randomElement($districts),
            'country' => 'বাংলাদেশ',
            'postal_code' => fake()->numerify('####'),
            'bio' => fake()->sentence(10),
            'occupation' => fake()->randomElement(['শিক্ষার্থী', 'চাকরিজীবী', 'ব্যবসায়ী', 'গৃহিণী', 'শিক্ষক']),
            'education_level' => fake()->randomElement(['এসএসসি', 'এইচএসসি', 'স্নাতক', 'স্নাতকোত্তর']),
            'is_active' => true,
            'is_verified' => fake()->boolean(80),
        ];
    }
}