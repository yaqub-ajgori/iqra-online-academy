<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethods = ['bkash', 'nagad', 'rocket', 'bank_transfer'];
        $method = fake()->randomElement($paymentMethods);
        
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'amount' => fake()->randomElement([500, 750, 1000, 1500, 2000, 2500, 3000]),
            'currency' => 'BDT',
            'payment_method' => $method,
            'transaction_id' => $this->generateTransactionId($method),
            'status' => fake()->randomElement(['completed', 'pending', 'failed']),
            'sender_number' => $method !== 'bank_transfer' ? '01' . fake()->numerify('#########') : null,
            'receiver_number' => $method !== 'bank_transfer' ? '01' . fake()->numerify('#########') : null,
            'bank_name' => $method === 'bank_transfer' ? fake()->randomElement(['Dutch Bangla Bank', 'Brac Bank', 'City Bank', 'Eastern Bank']) : null,
            'account_number' => $method === 'bank_transfer' ? fake()->numerify('##############') : null,
            'branch_name' => $method === 'bank_transfer' ? fake()->city() . ' Branch' : null,
        ];
    }

    /**
     * Generate realistic transaction ID based on payment method.
     */
    private function generateTransactionId(string $method): string
    {
        switch ($method) {
            case 'bkash':
                return 'BKA' . fake()->numerify('##########');
            case 'nagad':
                return 'NGD' . fake()->numerify('##########');
            case 'rocket':
                return 'RKT' . fake()->numerify('##########');
            case 'bank_transfer':
                return 'BT' . fake()->numerify('############');
            default:
                return fake()->numerify('############');
        }
    }

    /**
     * Create a completed payment.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Create a pending payment.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}