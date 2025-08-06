<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $courses = [
            ['title' => 'কুরআন তিলাওয়াত ও তাজবীদ শিক্ষা', 'description' => 'পবিত্র কুরআন সুন্দরভাবে তিলাওয়াত করার জন্য তাজবীদের নিয়মকানুন শিখুন।'],
            ['title' => 'হাদিস শরীফ অধ্যয়ন', 'description' => 'রাসূল (সা.) এর পবিত্র হাদিস সমূহের অর্থ ও ব্যাখ্যা জানুন।'],
            ['title' => 'ইসলামী ফিকহ ও আইনশাস্ত্র', 'description' => 'দৈনন্দিন জীবনে ইসলামী আইনের প্রয়োগ ও মাসায়েল শিখুন।'],
            ['title' => 'আরবি ভাষা শিক্ষা - প্রাথমিক', 'description' => 'আরবি ভাষার বর্ণমালা থেকে শুরু করে মৌলিক ব্যাকরণ শিখুন।'],
            ['title' => 'সীরাতুন্নবী (সা.)', 'description' => 'মহানবী হযরত মুহাম্মদ (সা.) এর জীবনী ও আদর্শ জানুন।'],
            ['title' => 'ইসলামী আকীদা ও বিশ্বাস', 'description' => 'ইসলামের মৌলিক বিশ্বাস ও আকীদা সম্পর্কে বিস্তারিত জানুন।'],
            ['title' => 'হজ্জ ও উমরাহ গাইড', 'description' => 'হজ্জ ও উমরাহর সম্পূর্ণ নিয়মকানুন ও দোয়া-দুরুদ শিখুন।'],
            ['title' => 'ইসলামী পারিবারিক জীবন', 'description' => 'ইসলামী শিক্ষা অনুযায়ী পারিবারিক জীবন পরিচালনার উপায়।'],
            ['title' => 'জাকাত ও দান-সদকার নিয়মাবলী', 'description' => 'জাকাত ও বিভিন্ন প্রকার দানের ইসলামী নিয়মকানুন।'],
            ['title' => 'দোয়া ও জিকির সংকলন', 'description' => 'দৈনন্দিন জীবনের গুরুত্বপূর্ণ দোয়া ও জিকির শিখুন।']
        ];

        $course = $this->faker->randomElement($courses);
        $price = $this->faker->randomElement([0, 500, 1000, 1500, 2000, 2500, 3000]);
        
        return [
            'title' => $course['title'],
            'slug' => Str::slug($course['title']) . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'full_description' => $course['description'] . "\n\n" . $this->faker->paragraphs(3, true),
            'category_id' => CourseCategory::factory(),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'instructor_id' => Teacher::factory(),
            'thumbnail_image' => 'courses/thumbnails/default.jpg',
            'price' => $price,
            'currency' => 'BDT',
            'discount_price' => $price > 0 ? $price * 0.8 : null,
            'discount_expires_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'duration_in_minutes' => $this->faker->numberBetween(180, 1200), // 3-20 hours
            'status' => $this->faker->randomElement(['published', 'published', 'published', 'draft']), // More published
            'is_featured' => $this->faker->boolean(30), // 30% chance
            'is_free' => $price === 0,
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
            'is_free' => true,
            'discount_price' => null,
        ]);
    }
}