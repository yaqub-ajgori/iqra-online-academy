<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseReview>
 */
class CourseReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = [
            'মাশাআল্লাহ! খুবই সুন্দর একটি কোর্স। অনেক কিছু শিখতে পারলাম।',
            'আলহামদুলিল্লাহ! উস্তাদের ব্যাখ্যা অসাধারণ ছিল।',
            'বারাকাল্লাহু ফিকুম। কোর্সটি আমার জন্য খুবই উপকারী হয়েছে।',
            'সুবহানাল্লাহ! এত সুন্দর করে বুঝিয়ে দেওয়ার জন্য ধন্যবাদ।',
            'জাজাকাল্লাহু খাইরান। কোর্সের মান অনেক ভালো।',
            'আল্লাহ আপনাদের উত্তম প্রতিদান দান করুন। খুবই ভালো লাগলো।',
            'মাশাআল্লাহ! প্রতিটি লেসন খুবই স্পষ্ট এবং বোধগম্য।',
            'আলহামদুলিল্লাহ! এই কোর্স আমার ঈমান বৃদ্ধি করেছে।',
            'বারাকাল্লাহ! সবার জন্য এই কোর্স নেওয়া উচিত।'
        ];

        return [
            'course_id' => Course::factory(),
            'student_id' => Student::factory(),
            'user_id' => null, // Will be set by seeder
            'review_text' => fake()->randomElement($reviews),
            'rating' => fake()->numberBetween(3, 5),
            'title' => fake()->boolean(40) ? fake()->sentence(4) : null,
            'status' => fake()->randomElement(['approved', 'pending', 'rejected']),
            'approved_at' => null,
            'approved_by' => null,
            'moderation_notes' => null,
            'is_featured' => fake()->boolean(10),
            'is_verified_purchase' => fake()->boolean(90),
            'helpful_count' => fake()->numberBetween(0, 25),
            'reported_count' => 0,
            'metadata' => json_encode(['completion_status' => 'completed']),
        ];
    }

    /**
     * Create an approved review.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Create a 5-star review.
     */
    public function fiveStar(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
        ]);
    }
}