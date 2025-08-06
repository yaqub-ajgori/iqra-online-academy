<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            CourseCategorySeeder::class,
            CourseSeeder::class,
            CourseContentSeeder::class,
            EnrollmentSeeder::class,
            QuizSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}