<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseEnrollment>
 */
class CourseEnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enrollmentTypes = ['paid', 'free'];
        $type = fake()->randomElement($enrollmentTypes);
        
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'enrolled_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'enrollment_type' => $type,
            'payment_id' => $type === 'paid' ? Payment::factory() : null,
            'amount_paid' => $type === 'paid' ? fake()->randomFloat(2, 500, 3000) : 0,
            'currency' => 'BDT',
            'payment_status' => $type === 'paid' ? fake()->randomElement(['completed', 'pending']) : 'completed',
            'progress_percentage' => fake()->randomFloat(2, 0, 100),
            'lessons_completed' => fake()->numberBetween(0, 20),
            'is_active' => true,
            'is_completed' => fake()->boolean(30),
        ];
    }

    /**
     * Create a completed enrollment.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'progress_percentage' => 100,
        ]);
    }

    /**
     * Create a free enrollment.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'enrollment_type' => 'free',
            'payment_id' => null,
            'amount_paid' => 0,
            'payment_status' => 'completed',
        ]);
    }

    /**
     * Create a paid enrollment.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'enrollment_type' => 'paid',
            'payment_id' => Payment::factory(),
            'payment_status' => 'completed',
        ]);
    }
}