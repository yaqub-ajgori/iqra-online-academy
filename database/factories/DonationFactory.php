<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purposes = [
            'সাধারণ দান',
            'গরীব ছাত্র সহায়তা',
            'মসজিদ নির্মাণ',
            'অনাথ শিশু সহায়তা',
            'ইসলামি শিক্ষা প্রসার',
            'বিধবা মা সহায়তা',
            'মাদরাসা উন্নয়ন',
            'বই বিতরণ',
            'ইফতার বিতরণ',
            'যাকাতুল ফিতর'
        ];

        $paymentMethods = ['bkash', 'nagad', 'rocket', 'bank_transfer'];
        $method = fake()->randomElement($paymentMethods);

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '01' . fake()->numerify('#########'),
            'amount' => fake()->randomElement([100, 200, 500, 1000, 2000, 5000, 10000]),
            'payment_method' => $method,
            'transaction_id' => $this->generateTransactionId($method),
            'reason' => fake()->randomElement($purposes),
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
     * Create a donation with a specific reason.
     */
    public function withReason(string $reason): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => $reason,
        ]);
    }
}