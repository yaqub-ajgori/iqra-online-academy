<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'certificate_number' => 'IQRA-' . fake()->unique()->numerify('######'),
            'student_name' => fake()->name(),
            'course_title' => fake()->sentence(4),
            'course_description' => fake()->paragraph(2),
            'instructors' => json_encode([fake()->name(), fake()->name()]),
            'final_score' => fake()->numberBetween(70, 100),
            'completion_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'issue_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'expiry_date' => null,
            'certificate_path' => null,
            'template_used' => 'default',
            'verification_code' => fake()->unique()->regexify('[A-Z0-9]{12}'),
            'is_verified' => true,
            'is_revoked' => false,
            'revoked_at' => null,
            'revocation_reason' => null,
            'metadata' => json_encode(['generated_by' => 'seeder']),
        ];
    }

    /**
     * Create an excellent grade certificate.
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => 'A+',
            'score_percentage' => fake()->numberBetween(90, 100),
        ]);
    }
}