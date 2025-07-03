<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'user_id' => 1,
                'full_name' => 'Ayesha Rahman',
                'phone' => '01720000001',
                'date_of_birth' => Carbon::parse('2000-01-15'),
                'gender' => 'female',
                'address' => '123 Main Street',
                'city' => 'Dhaka',
                'district' => 'Dhaka',
                'country' => 'Bangladesh',
                'postal_code' => '1207',
                'profile_picture' => null,
                'bio' => 'Enthusiastic learner.',
                'occupation' => 'Student',
                'education_level' => 'Undergraduate',
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'user_id' => 2,
                'full_name' => 'Imran Hossain',
                'phone' => '01720000002',
                'date_of_birth' => Carbon::parse('1998-05-22'),
                'gender' => 'male',
                'address' => '456 College Road',
                'city' => 'Chittagong',
                'district' => 'Chittagong',
                'country' => 'Bangladesh',
                'postal_code' => '4000',
                'profile_picture' => null,
                'bio' => 'Math lover.',
                'occupation' => 'Student',
                'education_level' => 'Graduate',
                'is_active' => true,
                'is_verified' => false,
            ],
            [
                'user_id' => 3,
                'full_name' => 'Sadia Karim',
                'phone' => '01720000003',
                'date_of_birth' => Carbon::parse('2002-09-10'),
                'gender' => 'female',
                'address' => '789 School Lane',
                'city' => 'Rajshahi',
                'district' => 'Rajshahi',
                'country' => 'Bangladesh',
                'postal_code' => '6000',
                'profile_picture' => null,
                'bio' => 'Aspiring scientist.',
                'occupation' => 'Student',
                'education_level' => 'HSC',
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'user_id' => 4,
                'full_name' => 'Tanvir Ahmed',
                'phone' => '01720000004',
                'date_of_birth' => Carbon::parse('1999-12-01'),
                'gender' => 'male',
                'address' => '321 University Ave',
                'city' => 'Khulna',
                'district' => 'Khulna',
                'country' => 'Bangladesh',
                'postal_code' => '9000',
                'profile_picture' => null,
                'bio' => 'Business enthusiast.',
                'occupation' => 'Student',
                'education_level' => 'Undergraduate',
                'is_active' => false,
                'is_verified' => false,
            ],
            [
                'user_id' => 5,
                'full_name' => 'Mitu Sultana',
                'phone' => '01720000005',
                'date_of_birth' => Carbon::parse('2001-03-18'),
                'gender' => 'female',
                'address' => '654 College Road',
                'city' => 'Sylhet',
                'district' => 'Sylhet',
                'country' => 'Bangladesh',
                'postal_code' => '3100',
                'profile_picture' => null,
                'bio' => 'Language lover.',
                'occupation' => 'Student',
                'education_level' => 'Graduate',
                'is_active' => true,
                'is_verified' => true,
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
} 