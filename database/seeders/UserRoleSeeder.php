<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@iqra-academy.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        UserRole::create([
            'user_id' => $adminUser->id,
            'role_type' => 'admin',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        // Create Teachers (simplified structure)
        $teachers = [
            [
                'full_name' => 'উস্তাদ মোহাম্মদ রহমান',
                'speciality' => 'কুরআন তিলাওয়াত ও তাজবীদ',
                'experience' => '15+ years',
                'phone' => '01712345678',
                'is_active' => true,
            ],
            [
                'full_name' => 'উস্তাদ আবদুল কারিম',
                'speciality' => 'হাদিস শরীফ ও সীরাত',
                'experience' => '12+ years',
                'phone' => '01812345678',
                'is_active' => true,
            ],
            [
                'full_name' => 'উস্তাদ মোহাম্মদ হাসান',
                'speciality' => 'ইসলামিক ফিকহ ও আইন',
                'experience' => '18+ years',
                'phone' => '01912345678',
                'is_active' => true,
            ],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }

        // Create Student Users
        $students = [
            [
                'name' => 'আবদুল্লাহ আহমেদ',
                'email' => 'abdullah@example.com',
                'full_name' => 'আবদুল্লাহ আহমেদ',
                'phone' => '01612345678',
                'city' => 'ঢাকা',
                'education_level' => 'university',
            ],
            [
                'name' => 'ফাতিমা খাতুন',
                'email' => 'fatima@example.com',
                'full_name' => 'ফাতিমা খাতুন',
                'phone' => '01712345679',
                'city' => 'চট্টগ্রাম',
                'education_level' => 'college',
            ],
            [
                'name' => 'মোহাম্মদ ইব্রাহিম',
                'email' => 'ibrahim@example.com',
                'full_name' => 'মোহাম্মদ ইব্রাহিম',
                'phone' => '01812345679',
                'city' => 'সিলেট',
                'education_level' => 'high_school',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_type' => 'student',
                'is_active' => true,
                'assigned_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'full_name' => $studentData['full_name'],
                'phone' => $studentData['phone'],
                'city' => $studentData['city'],
                'country' => 'Bangladesh',
                'education_level' => $studentData['education_level'],
                'is_active' => true,
                'is_verified' => true,
            ]);
        }
    }
}
