<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific teachers
        Teacher::create([
            'full_name' => 'উস্তাদ আবু বকর সিদ্দিক',
            'speciality' => 'কুরআন তিলাওয়াত ও তাজবীদ',
            'experience' => '২০ বছরের শিক্ষকতার অভিজ্ঞতা',
            'profile_picture' => 'teachers/default-avatar.jpg',
            'phone' => '01711234567',
            'is_active' => true,
        ]);

        Teacher::create([
            'full_name' => 'হাফেজ মুহাম্মদ ইব্রাহিম',
            'speciality' => 'হাদিস শরীফ',
            'experience' => '১৫ বছর ইসলামী শিক্ষায় অভিজ্ঞতা',
            'profile_picture' => 'teachers/default-avatar.jpg',
            'phone' => '01811234567',
            'is_active' => true,
        ]);

        // Create additional teachers using factory
        Teacher::factory()->count(15)->create();
    }
}