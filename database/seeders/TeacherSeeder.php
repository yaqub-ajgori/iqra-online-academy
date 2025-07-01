<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $specialities = [
            'Islamic Studies',
            'Quran Recitation',
            'Arabic Language',
            'Islamic History',
            'Fiqh (Islamic Jurisprudence)',
            'Hadith Studies',
            'Tajweed',
            'Islamic Ethics',
            'Islamic Economics',
            'Islamic Architecture'
        ];

        $experiences = [
            '1-3 years',
            '3-5 years',
            '5-10 years',
            '10-15 years',
            '15+ years'
        ];

        // Create 10 sample teachers
        for ($i = 0; $i < 10; $i++) {
            Teacher::create([
                'full_name' => $faker->name(),
                'speciality' => $faker->randomElement($specialities),
                'experience' => $faker->randomElement($experiences),
                'profile_picture' => null, // Will be handled by frontend upload
                'phone' => $faker->phoneNumber(),
                'is_active' => $faker->boolean(80), // 80% chance of being active
            ]);
        }
    }
}
