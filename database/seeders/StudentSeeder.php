<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific student
        $studentUser = User::where('email', 'student@iqra.academy')->first();
        
        if ($studentUser) {
            Student::create([
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
        }

        // Create additional students from remaining users
        $users = User::whereNotIn('email', ['admin@iqra.academy', 'student@iqra.academy', 'teacher@iqra.academy'])
                     ->take(15)  // Reduced number for simpler testing
                     ->get();

        foreach ($users as $user) {
            Student::factory()->create(['user_id' => $user->id]);
        }
    }
}