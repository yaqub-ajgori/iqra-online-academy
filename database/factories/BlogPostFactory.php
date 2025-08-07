<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'কুরআন তিলাওয়াতের আদব ও নিয়মাবলী',
            'নামাজের গুরুত্ব ও ফজিলত',
            'ইসলামে জ্ঞানার্জনের গুরুত্ব',
            'রমজান মাসের তাৎপর্য ও করণীয়',
            'হজ্জের নিয়ম ও ফজিলত',
            'যাকাতের বিধান ও গুরুত্ব',
            'ইসলামে পারিবারিক জীবনের আদর্শ',
            'যুব সমাজের জন্য ইসলামি দিকনির্দেশনা',
            'তাওবার গুরুত্ব ও শর্তাবলী',
            'ইসলামে শিক্ষার গুরুত্ব',
            'সদকার ফজিলত ও নিয়মাবলী',
            'ইসলামি শিষ্টাচারের গুরুত্ব',
            'কুরআন হিফজের ফজিলত',
            'হাদিস অধ্যয়নের গুরুত্ব',
            'ইসলামে মাতা-পিতার সম্মান'
        ];

        $title = fake()->randomElement($titles) . ' - ' . fake()->numberBetween(1, 1000);
        
        $content = fake()->paragraphs(8, true) . "\n\n" .
                  "আল্লাহ তায়ালা আমাদের সবাইকে সঠিক পথে চলার তওফিক দান করুন। আমীন।";

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->numerify('###'),
            'excerpt' => fake()->paragraph(2),
            'content' => $content,
            'author_id' => User::factory(),
            'category_id' => BlogCategory::factory(),
            'status' => fake()->randomElement(['published', 'draft', 'review']),
            'is_featured' => fake()->boolean(20),
            'is_sticky' => fake()->boolean(10),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'reading_time' => fake()->numberBetween(3, 15),
            'views_count' => fake()->numberBetween(0, 1000),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(15),
            'meta_keywords' => implode(', ', fake()->words(6)),
        ];
    }

    /**
     * Create a published post.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Create a featured post.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}