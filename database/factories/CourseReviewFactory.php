<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Models\CourseReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseReviewFactory extends Factory
{
    public function definition(): array
    {
        $islamicReviews = [
            [
                'title' => 'অসাধারণ ইসলামিক শিক্ষা',
                'text' => 'মাশাআল্লাহ! এই কোর্সটি আমার ইসলামিক জ্ঞানে অনেক সমৃদ্ধি এনেছে। শিক্ষকের পদ্ধতি খুবই কার্যকর এবং বোধগম্য।'
            ],
            [
                'title' => 'জীবন পরিবর্তনকারী কোর্স',
                'text' => 'আলহামদুলিল্লাহ! এই কোর্সের মাধ্যমে কুরআনের গভীর অর্থ বুঝতে পেরেছি। প্রতিটি ক্লাস খুবই মানসম্পন্ন ও তথ্যবহুল।'
            ],
            [
                'title' => 'চমৎকার হাদিস ব্যাখ্যা',
                'text' => 'রাসূল (সা.) এর হাদিসের ব্যাখ্যা এত সুন্দরভাবে দেওয়া হয়েছে যা আমার হৃদয়ে গভীর প্রভাব ফেলেছে। বারাকাল্লাহু ফীকুম!'
            ],
            [
                'title' => 'ব্যতিক্রমী ফিকহ শিক্ষা',
                'text' => 'দৈনন্দিন জীবনের ইসলামী আইনকানুন এত সহজভাবে শেখানো হয়েছে যা আমার জীবনযাত্রায় ইতিবাচক পরিবর্তন এনেছে।'
            ],
            [
                'title' => 'আধ্যাত্মিক উন্নতি',
                'text' => 'সুবহানাল্লাহ! এই কোর্স শুধু জ্ঞানই দেয়নি, আধ্যাত্মিক উন্নতিও এনেছে। আল্লাহ তাআলা শিক্ষকদের উত্তম প্রতিদান দিন।'
            ],
            [
                'title' => 'সময়োপযোগী শিক্ষা',
                'text' => 'আধুনিক যুগের সাথে ইসলামিক শিক্ষার চমৎকার সমন্বয়। অনলাইনে এত মানসম্পন্ন ইসলামিক কোর্স পেয়ে কৃতজ্ঞ।'
            ],
            [
                'title' => 'হৃদয়স্পর্শী শিক্ষা',
                'text' => 'শুধু মুখস্থ নয়, বরং অন্তরে ইসলামের মূল্যবোধ প্রবেশ করিয়ে দিয়েছে এই কোর্স। জাযাকাল্লাহু খায়রান!'
            ],
            [
                'title' => 'পরিবার ও সমাজের জন্য কল্যাণকর',
                'text' => 'এই কোর্স শুধু আমার নয়, আমার পুরো পরিবারের জন্য কল্যাণ বয়ে এনেছে। সবাইকে এই কোর্স করার পরামর্শ দিই।'
            ]
        ];

        $review = $this->faker->randomElement($islamicReviews);
        $rating = $this->faker->numberBetween(4, 5); // Mostly high ratings for Islamic courses
        
        return [
            'course_id' => Course::factory(),
            'student_id' => Student::factory(),
            'user_id' => User::factory(),
            'title' => $review['title'],
            'review_text' => $review['text'],
            'rating' => $rating,
            'status' => $this->faker->randomElement(['approved', 'approved', 'approved', 'pending']), // Mostly approved
            'approved_at' => $this->faker->boolean(80) ? $this->faker->dateTimeBetween('-3 months', 'now') : null,
            'approved_by' => $this->faker->boolean(80) ? User::factory() : null,
            'is_featured' => $this->faker->boolean(15), // 15% chance of being featured
            'is_verified_purchase' => $this->faker->boolean(90), // 90% are verified purchases
            'helpful_count' => $this->faker->numberBetween(0, 25),
            'reported_count' => $this->faker->numberBetween(0, 2),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseReview::STATUS_APPROVED,
            'approved_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'approved_by' => User::factory(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseReview::STATUS_PENDING,
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'status' => CourseReview::STATUS_APPROVED,
            'rating' => $this->faker->numberBetween(4, 5),
            'approved_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function verifiedPurchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified_purchase' => true,
        ]);
    }

    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
        ]);
    }
}