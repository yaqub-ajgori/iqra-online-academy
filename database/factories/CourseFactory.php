<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseTitles = [
            'কুরআন তিলাওয়াত ও তাজবীদ - প্রাথমিক',
            'হাদিস শরীফ পাঠ - মধ্যম স্তর',
            'ইসলামিক ফিকহ - দৈনন্দিন মাসায়েল',
            'আরবি ভাষা শিক্ষা - শুরু থেকে শেষ',
            'ইসলামের ইতিহাস - খিলাফতে রাশেদা',
            'তাফসীর - সূরা ফাতিহা থেকে সূরা বাকারা',
            'আকিদা ও বিশ্বাস - মৌলিক শিক্ষা',
            'ইসলামি আইনশাস্ত্র - পরিচিতি',
            'দুআ ও যিকির - দৈনন্দিন আমল',
            'ইসলামি শিষ্টাচার - পূর্ণাঙ্গ কোর্স',
            'কুরআন হিফজ - ত্রিশ পারা',
            'নামাজ শিক্ষা - সঠিক নিয়ম',
            'রোজা ও হজ্জের বিধান',
            'যাকাত ও সদকার নিয়মাবলী',
            'বিবাহ ও পারিবারিক জীবন'
        ];

        $descriptions = [
            'এই কোর্সে আপনি ইসলামের মৌলিক বিষয়গুলি সম্পর্কে বিস্তারিত জানতে পারবেন।',
            'কুরআন ও হাদিসের আলোকে জীবন পরিচালনার সঠিক পদ্ধতি শিখুন।',
            'ইসলামি শিক্ষার এই কোর্সটি আপনার ধর্মীয় জ্ঞান বৃদ্ধি করবে।',
            'প্রাথমিক থেকে উন্নত পর্যায় পর্যন্ত সম্পূর্ণ গাইডলাইন।',
            'অভিজ্ঞ উস্তাদদের তত্ত্বাবধানে সঠিক শিক্ষা গ্রহণ করুন।'
        ];

        $title = fake()->randomElement($courseTitles) . ' - পর্ব ' . fake()->numberBetween(1, 1000);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->numerify('####'),
            'full_description' => fake()->randomElement($descriptions) . ' ' . fake()->paragraph(3),
            'category_id' => CourseCategory::factory(),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'instructor_id' => Teacher::factory(),
            'price' => fake()->randomElement([500, 750, 1000, 1500, 2000, 2500, 3000]),
            'currency' => 'BDT',
            'discount_price' => null,
            'discount_expires_at' => null,
            'duration_in_minutes' => fake()->numberBetween(300, 1800),
            'status' => fake()->randomElement(['published', 'draft', 'review']),
            'is_featured' => fake()->boolean(20),
            'is_free' => fake()->boolean(15),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a published course.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Create a free course.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free' => true,
            'price' => 0,
        ]);
    }

    /**
     * Create a featured course.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}