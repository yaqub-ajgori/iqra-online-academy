<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->count(10)->create();

        // admin user
        $admin = User::create([
            'name' => 'Md Saeedul Mostafa',
            'email' => 'saeedul.mostafa@gmail.com',
            'password' => Hash::make('saeedul.mostafa@gmail.com'),
            'email_verified_at' => now(),
        ]);

        UserRole::create([
            'user_id' => $admin->id,
            'role_type' => 'admin',
        ]);

        $this->call([
            // CourseCategorySeeder::class,
            BlogSeeder::class,
            // TeacherSeeder::class,
            // CourseSeeder::class,
            // StudentSeeder::class,
            // DonationSeeder::class,
        ]);
    }
}
