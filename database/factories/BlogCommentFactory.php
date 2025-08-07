<?php

namespace Database\Factories;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogComment>
 */
class BlogCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comments = [
            'মাশাআল্লাহ! খুবই উপকারী পোস্ট। জাজাকাল্লাহু খাইরান।',
            'বারাকাল্লাহু ফিকুম। অনেক কিছু জানতে পারলাম।',
            'আল্লাহ আপনাকে উত্তম প্রতিদান দান করুন।',
            'খুবই সুন্দর ব্যাখ্যা। ধন্যবাদ।',
            'আলহামদুলিল্লাহ! এমন লেখার জন্য অপেক্ষায় ছিলাম।',
            'আল্লাহ তায়ালা আপনাকে আরো এই ধরনের লেখা লিখার তওফিক দান করুন।',
            'সুবহানাল্লাহ! অনেক উপকার হলো।',
            'মাশাআল্লাহ! চমৎকার পোস্ট।',
            'আল্লাহ আপনার ইলমে বরকত দান করুন।'
        ];

        return [
            'blog_post_id' => BlogPost::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'author_name' => fake()->name(),
            'author_email' => fake()->safeEmail(),
            'content' => fake()->randomElement($comments),
            'status' => fake()->randomElement(['approved', 'pending', 'spam']),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Create an approved comment.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Create a reply comment.
     */
    public function reply(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => BlogComment::factory(),
        ]);
    }
}