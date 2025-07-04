<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'full_name' => 'Md Yaqub Ajgori',
                'speciality' => 'Web Development',
                'experience' => '5 years',
                'profile_picture' => null,
                'phone' => '01710000001',
                'is_active' => true,
            ],
            [
                'full_name' => 'Md Saeedul Mostafa',
                'speciality' => 'Mathematics',
                'experience' => '8 years',
                'profile_picture' => null,
                'phone' => '01710000002',
                'is_active' => true,
            ],
            [
                'full_name' => 'Md Ayub Ajgori',
                'speciality' => 'Physics',
                'experience' => '6 years',
                'profile_picture' => null,
                'phone' => '01710000003',
                'is_active' => true,
            ],
            [
                'full_name' => 'Md Ismail',
                'speciality' => 'English Language',
                'experience' => '4 years',
                'profile_picture' => null,
                'phone' => '01710000004',
                'is_active' => true,
            ],
            [
                'full_name' => 'Karim Ullah',
                'speciality' => 'Business Studies',
                'experience' => '7 years',
                'profile_picture' => null,
                'phone' => '01710000005',
                'is_active' => true,
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
} 