<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        $completionDate = $this->faker->dateTimeBetween('-3 months', 'now');
        
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'certificate_number' => 'IOA-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'verification_code' => strtoupper(Str::random(12)),
            'student_name' => 'আবদুল্লাহ রহমান',
            'course_title' => 'কুরআন তিলাওয়াত ও তাজবীদ শিক্ষা',
            'course_description' => 'পবিত্র কুরআন সুন্দরভাবে তিলাওয়াত করার জন্য তাজবীদের নিয়মকানুন।',
            'instructors' => ['উস্তাদ আবু বকর', 'হাফেজ উমর ফারুক'],
            'final_score' => $this->faker->numberBetween(75, 95),
            'completion_date' => $completionDate,
            'issue_date' => $completionDate,
            'expiry_date' => $this->faker->optional()->dateTimeBetween('+1 year', '+5 years'),
            'certificate_path' => 'certificates/sample-certificate.pdf',
            'is_revoked' => false,
            'revoked_at' => null,
            'revocation_reason' => null,
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_revoked' => true,
            'revoked_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'revocation_reason' => 'Administrative decision',
        ]);
    }
}