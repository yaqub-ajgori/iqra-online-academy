<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Donation::insert([
            [
                'name' => 'আব্দুল্লাহ আল মামুন',
                'email' => 'abdullah@example.com',
                'phone' => '01711111111',
                'amount' => 1000,
                'payment_method' => 'bkash',
                'transaction_id' => 'TXN1001',
                'reason' => 'মাদ্রাসার জন্য',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'সাবরিনা আক্তার',
                'email' => 'sabrina@example.com',
                'phone' => '01822222222',
                'amount' => 500,
                'payment_method' => 'nagad',
                'transaction_id' => 'TXN2002',
                'reason' => 'শিক্ষার্থীদের বৃত্তি',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'রাশেদুল ইসলাম',
                'email' => 'rashed@example.com',
                'phone' => '01933333333',
                'amount' => 1500,
                'payment_method' => 'rocket',
                'transaction_id' => 'TXN3003',
                'reason' => 'ইসলামিক বই বিতরণ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 