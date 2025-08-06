<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        $islamicTitles = [
            'উস্তাদ', 'হাফেজ', 'মাওলানা', 'মুফতি', 'আলেম', 'কারী', 'হাফেজা', 'আলেমা'
        ];

        $specializations = [
            'কুরআন তিলাওয়াত ও তাজবীদ',
            'হাদিস শরীফ',
            'ফিকহ ও ইসলামী আইন',
            'আরবি ভাষা ও সাহিত্য',
            'ইসলামের ইতিহাস',
            'আকীদা ও দর্শন',
            'দাওয়াহ ও তাবলীগ',
            'ইসলামী অর্থনীতি'
        ];

        $experiences = [
            '২০ বছরের শিক্ষকতার অভিজ্ঞতা',
            '১৫ বছর ইসলামী শিক্ষায় অভিজ্ঞতা',
            '১০ বছর কুরআন শিক্ষায় দক্ষতা',
            '২৫ বছর ধর্মীয় শিক্ষাদান'
        ];

        return [
            'full_name' => $this->faker->randomElement($islamicTitles) . ' ' . $this->faker->name(),
            'speciality' => $this->faker->randomElement($specializations),
            'experience' => $this->faker->randomElement($experiences),
            'profile_picture' => 'teachers/default-avatar.jpg',
            'phone' => $this->faker->regexify('01[3-9][0-9]{8}'),
            'is_active' => true,
        ];
    }
}