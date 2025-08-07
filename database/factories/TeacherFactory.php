<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teacherNames = [
            'মাওলানা আব্দুল করিম', 'হাফেজ মোহাম্মদ আলি', 'মাওলানা ইউসুফ আহমদ', 
            'ড. ফাতেমা খাতুন', 'মাওলানা নূরুল ইসলাম', 'হাফেজা আয়েশা সিদ্দিকা',
            'উস্তাদ আহমদুল হক', 'মাওলানা রফিকুল ইসলাম', 'ড. খাদিজা বেগম'
        ];

        $specialities = [
            'কুরআন তিলাওয়াত ও তাজবীদ', 'হাদিস শরীফ', 'ইসলামিক ফিকহ', 'আরবি ভাষা ও ব্যাকরণ',
            'ইসলামের ইতিহাস', 'তাফসীর', 'আকিদা ও বিশ্বাস', 'ইসলামিক আইনশাস্ত্র',
            'কুরআন হিফজ', 'ইসলামি দর্শন'
        ];

        return [
            'user_id' => User::factory(),
            'full_name' => fake()->randomElement($teacherNames),
            'speciality' => fake()->randomElement($specialities),
            'experience' => fake()->numberBetween(2, 25) . ' বছর',
            'phone' => '01' . fake()->numberBetween(3, 9) . fake()->numerify('########'),
            'is_active' => true,
        ];
    }
}