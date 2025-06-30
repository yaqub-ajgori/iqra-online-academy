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

        // Create Teacher Users
        $teachers = [
            [
                'name' => 'উস্তাদ মোহাম্মদ রহমান',
                'email' => 'rahman@iqra-academy.com',
                'full_name' => 'উস্তাদ মোহাম্মদ রহমান',
                'phone' => '01712345678',
                'bio' => 'কুরআন তিলাওয়াত ও তাজবীদের বিশেষজ্ঞ। ১৫ বছরের শিক্ষকতার অভিজ্ঞতা।',
                'specializations' => ['কুরআন', 'তাজবীদ', 'হিফজ'],
                'teaching_experience_years' => 15,
                'islamic_teaching_experience_years' => 15,
            ],
            [
                'name' => 'উস্তাদ আবদুল কারিম',
                'email' => 'karim@iqra-academy.com',
                'full_name' => 'উস্তাদ আবদুল কারিম',
                'phone' => '01812345678',
                'bio' => 'হাদিস শরীফ ও সীরাতের গবেষক। আল-আজহার বিশ্ববিদ্যালয়ের স্নাতক।',
                'specializations' => ['হাদিস', 'সীরাত', 'আরবি'],
                'teaching_experience_years' => 12,
                'islamic_teaching_experience_years' => 12,
            ],
            [
                'name' => 'উস্তাদ মোহাম্মদ হাসান',
                'email' => 'hasan@iqra-academy.com',
                'full_name' => 'উস্তাদ মোহাম্মদ হাসান',
                'phone' => '01912345678',
                'bio' => 'ইসলামিক ফিকহ ও আইনের বিশেষজ্ঞ। জামিয়া ইসলামিয়া পটিয়ার সাবেক শিক্ষক।',
                'specializations' => ['ফিকহ', 'আকিদা', 'ইসলামিক আইন'],
                'teaching_experience_years' => 18,
                'islamic_teaching_experience_years' => 18,
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_type' => 'teacher',
                'is_active' => true,
                'assigned_at' => now(),
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'full_name' => $teacherData['full_name'],
                'phone' => $teacherData['phone'],
                'bio' => $teacherData['bio'],
                'specializations' => $teacherData['specializations'],
                'teaching_experience_years' => $teacherData['teaching_experience_years'],
                'islamic_teaching_experience_years' => $teacherData['islamic_teaching_experience_years'],
                'is_verified' => true,
                'verification_status' => 'approved',
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'is_active' => true,
                'can_create_courses' => true,
                'average_rating' => 4.8,
            ]);
        }

        // Create Student Users
        $students = [
            [
                'name' => 'আবদুল্লাহ আহমেদ',
                'email' => 'abdullah@example.com',
                'full_name' => 'আবদুল্লাহ আহমেদ',
                'phone' => '01612345678',
                'city' => 'ঢাকা',
                'islamic_knowledge_level' => 'beginner',
            ],
            [
                'name' => 'ফাতিমা খাতুন',
                'email' => 'fatima@example.com',
                'full_name' => 'ফাতিমা খাতুন',
                'phone' => '01712345679',
                'city' => 'চট্টগ্রাম',
                'islamic_knowledge_level' => 'intermediate',
            ],
            [
                'name' => 'মোহাম্মদ ইব্রাহিম',
                'email' => 'ibrahim@example.com',
                'full_name' => 'মোহাম্মদ ইব্রাহিম',
                'phone' => '01812345679',
                'city' => 'সিলেট',
                'islamic_knowledge_level' => 'beginner',
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
                'islamic_knowledge_level' => $studentData['islamic_knowledge_level'],
                'preferred_language' => 'bengali',
                'is_active' => true,
                'is_verified' => true,
            ]);
        }
    }
}
