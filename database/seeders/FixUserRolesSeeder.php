<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class FixUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@iqra.academy')->first();
        $studentUser = User::where('email', 'student@iqra.academy')->first();
        $teacherUser = User::where('email', 'teacher@iqra.academy')->first();

        if ($adminUser && !$adminUser->roles()->exists()) {
            UserRole::create(['user_id' => $adminUser->id, 'role_type' => 'admin']);
            echo "Admin role created\n";
        }

        if ($studentUser && !$studentUser->roles()->exists()) {
            UserRole::create(['user_id' => $studentUser->id, 'role_type' => 'student']);
            echo "Student role created\n";
        }

        if ($teacherUser && !$teacherUser->roles()->exists()) {
            UserRole::create(['user_id' => $teacherUser->id, 'role_type' => 'teacher']);
            echo "Teacher role created\n";
        }

        echo "User roles fixed successfully!\n";
    }
}