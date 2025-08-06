<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BasicSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        $adminUser = User::create([
            'name' => 'অ্যাডমিন ইউজার',
            'email' => 'admin@iqra.academy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $studentUser = User::create([
            'name' => 'আবদুল্লাহ রহমান',
            'email' => 'student@iqra.academy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $teacherUser = User::create([
            'name' => 'উস্তাদ আবু বকর',
            'email' => 'teacher@iqra.academy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Assign roles to users
        UserRole::create([
            'user_id' => $adminUser->id,
            'role_type' => 'admin',
        ]);

        UserRole::create([
            'user_id' => $studentUser->id,
            'role_type' => 'student',
        ]);

        UserRole::create([
            'user_id' => $teacherUser->id,
            'role_type' => 'teacher',
        ]);

        // Create Teachers
        $teacher1 = Teacher::create([
            'full_name' => 'উস্তাদ আবু বকর সিদ্দিক',
            'speciality' => 'কুরআন তিলাওয়াত ও তাজবীদ',
            'experience' => '২০ বছরের শিক্ষকতার অভিজ্ঞতা',
            'phone' => '01711234567',
            'is_active' => true,
        ]);

        $teacher2 = Teacher::create([
            'full_name' => 'হাফেজ মুহাম্মদ ইব্রাহিম',
            'speciality' => 'হাদিস শরীফ',
            'experience' => '১৫ বছর ইসলামী শিক্ষায় অভিজ্ঞতা',
            'phone' => '01811234567',
            'is_active' => true,
        ]);

        // Create Student
        $student = Student::create([
            'user_id' => $studentUser->id,
            'full_name' => 'আবদুল্লাহ রহমান',
            'phone' => '01987654321',
            'date_of_birth' => '1995-05-15',
            'gender' => 'male',
            'address' => 'ধানমন্ডি, ঢাকা-১২০৫',
            'city' => 'ঢাকা',
            'country' => 'বাংলাদেশ',
            'education_level' => 'bachelors',
            'occupation' => 'সফটওয়্যার ইঞ্জিনিয়ার',
            'is_active' => true,
        ]);

        // Create Course Categories
        $categories = [
            'কুরআন শিক্ষা',
            'হাদিস শরীফ',
            'ফিকহ ও আইন',
            'আরবি ভাষা',
        ];

        foreach ($categories as $categoryName) {
            CourseCategory::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'is_active' => true,
            ]);
        }

        // Create Sample Courses
        $quranCategory = CourseCategory::where('name', 'কুরআন শিক্ষা')->first();
        
        Course::create([
            'title' => 'কুরআন তিলাওয়াত ও তাজবীদ শিক্ষা',
            'slug' => Str::slug('কুরআন তিলাওয়াত ও তাজবীদ শিক্ষা'),
            'full_description' => 'পবিত্র কুরআন সুন্দরভাবে তিলাওয়াত করার জন্য তাজবীদের নিয়মকানুন শিখুন।',
            'category_id' => $quranCategory->id,
            'level' => 'beginner',
            'instructor_id' => $teacher1->id,
            'price' => 0,
            'currency' => 'BDT',
            'duration_in_minutes' => 600,
            'status' => 'published',
            'is_featured' => true,
            'is_free' => true,
            'published_at' => now(),
        ]);

        $hadithCategory = CourseCategory::where('name', 'হাদিস শরীফ')->first();
        
        Course::create([
            'title' => 'হাদিস শরীফ অধ্যয়ন',
            'slug' => Str::slug('হাদিস শরীফ অধ্যয়ন'),
            'full_description' => 'রাসূল (সা.) এর পবিত্র হাদিস সমূহের অর্থ ও ব্যাখ্যা জানুন।',
            'category_id' => $hadithCategory->id,
            'level' => 'intermediate',
            'instructor_id' => $teacher2->id,
            'price' => 1500,
            'currency' => 'BDT',
            'discount_price' => 1200,
            'duration_in_minutes' => 900,
            'status' => 'published',
            'is_featured' => true,
            'is_free' => false,
            'published_at' => now(),
        ]);

        echo "Basic seeding completed successfully!\n";
        echo "Admin: admin@iqra.academy / password\n";
        echo "Student: student@iqra.academy / password\n";
        echo "Teacher: teacher@iqra.academy / password\n";
    }
}