<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::create([
            'name' => 'অ্যাডমিন ইউজার',
            'email' => 'admin@iqra.academy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Create Test Users
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

        // Create additional random users
        User::factory()->count(47)->create();
    }
}