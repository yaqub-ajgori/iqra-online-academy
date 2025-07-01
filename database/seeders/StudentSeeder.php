<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'phone' => $faker->optional()->phoneNumber(),
                'date_of_birth' => $faker->optional()->date('Y-m-d', '-5 years'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'address' => $faker->optional()->address(),
                'city' => $faker->optional()->city(),
                'district' => $faker->optional()->citySuffix(),
                'country' => 'Bangladesh',
                'postal_code' => $faker->optional()->postcode(),
                'profile_picture' => null,
                'bio' => $faker->optional()->sentence(10),
                'occupation' => $faker->optional()->jobTitle(),
                'education_level' => $faker->optional()->randomElement(['high_school', 'college', 'university']),
                'is_active' => $faker->boolean(90),
                'is_verified' => $faker->boolean(80),
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_type' => 'student',
                'is_active' => true,
                'assigned_at' => now(),
            ]);
        }
    }
}
