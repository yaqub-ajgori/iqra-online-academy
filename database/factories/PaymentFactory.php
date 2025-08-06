<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $amount = $this->faker->randomElement([500, 1000, 1500, 2000, 2500, 3000]);
        $paymentMethod = $this->faker->randomElement(['bkash', 'nagad', 'rocket', 'bank_transfer']);
        
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'amount' => $amount,
            'currency' => 'BDT',
            'payment_method' => $paymentMethod,
            'transaction_id' => $this->faker->unique()->regexify('[A-Z0-9]{10,15}'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'sender_number' => $paymentMethod !== 'bank_transfer' ? '01' . $this->faker->numerify('########') : null,
            'receiver_number' => $paymentMethod !== 'bank_transfer' ? '01712345678' : null,
            'bank_name' => $paymentMethod === 'bank_transfer' ? $this->faker->randomElement(['Dutch Bangla Bank', 'Brac Bank', 'City Bank']) : null,
            'account_number' => $paymentMethod === 'bank_transfer' ? $this->faker->numerify('############') : null,
            'branch_name' => $paymentMethod === 'bank_transfer' ? $this->faker->city : null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }

    public function bkash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'bkash',
            'sender_number' => '01' . $this->faker->numerify('########'),
            'receiver_number' => '01712345678',
            'bank_name' => null,
            'account_number' => null,
            'branch_name' => null,
        ]);
    }

    public function bankTransfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'bank_transfer',
            'sender_number' => null,
            'receiver_number' => null,
            'bank_name' => 'Dutch Bangla Bank',
            'account_number' => $this->faker->numerify('############'),
            'branch_name' => $this->faker->city,
        ]);
    }
}