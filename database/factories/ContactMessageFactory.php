<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        $subjects = [
            'কোর্স সম্পর্কে জানতে চাই', 'সার্টিফিকেট যাচাই সংক্রান্ত', 'পেমেন্ট সমস্যার সমাধান',
            'কোর্স সম্পন্ন হয়েছে কিনা', 'ওয়েবসাইট ফিডব্যাক', 'অ্যাকাউন্ট সংক্রান্ত সমস্যা'
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '01' . fake()->numerify('#########'),
            'subject' => fake()->randomElement($subjects),
            'message' => fake()->paragraph(3),
            'status' => fake()->randomElement(['unread', 'read', 'replied']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'metadata' => [
                'ip' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ],
            'read_at' => null,
            'replied_at' => null,
        ];
    }
}


