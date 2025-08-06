<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $bangladeshiNames = [
            'আবদুল্লাহ রহমান', 'ফাতিমা খাতুন', 'মুহাম্মদ ইব্রাহিম', 'আয়শা বেগম',
            'আবু বকর সিদ্দিক', 'খাদিজা আক্তার', 'উমর ফারুক', 'জয়নব খাতুন',
            'আলী হাসান', 'রুকাইয়া পারভীন', 'উসমান গনি', 'সুমাইয়া খাতুন'
        ];

        return [
            'user_id' => User::factory(),
            'full_name' => $this->faker->randomElement($bangladeshiNames),
            'phone' => $this->faker->regexify('01[3-9][0-9]{8}'), // Bangladeshi mobile format
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->randomElement([
                'ঢাকা, বাংলাদেশ',
                'চট্টগ্রাম, বাংলাদেশ', 
                'সিলেট, বাংলাদেশ',
                'রাজশাহী, বাংলাদেশ',
                'খুলনা, বাংলাদেশ',
                'বরিশাল, বাংলাদেশ',
                'রংপুর, বাংলাদেশ',
                'ময়মনসিংহ, বাংলাদেশ'
            ]),
            'city' => $this->faker->randomElement([
                'ঢাকা', 'চট্টগ্রাম', 'সিলেট', 'রাজশাহী', 
                'খুলনা', 'বরিশাল', 'রংপুর', 'ময়মনসিংহ'
            ]),
            'country' => 'বাংলাদেশ',
            'education_level' => $this->faker->randomElement([
                'primary', 'secondary', 'higher_secondary', 'bachelors', 'masters', 'phd'
            ]),
            'occupation' => $this->faker->randomElement([
                'ছাত্র/ছাত্রী', 'শিক্ষক', 'ব্যবসায়ী', 'ইঞ্জিনিয়ার', 
                'ডাক্তার', 'সরকারি কর্মচারী', 'গৃহিণী', 'অন্যান্য'
            ]),
            'is_active' => true,
        ];
    }
}