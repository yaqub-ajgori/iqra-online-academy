<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseLessonFactory extends Factory
{
    public function definition(): array
    {
        $lessons = [
            'প্রাথমিক পরিচিতি',
            'মূল বিষয়বস্তু',
            'ব্যাখ্যা ও বিশ্লেষণ',
            'উদাহরণ ও প্রয়োগ',
            'অনুশীলন ও পরীক্ষা',
            'পর্যালোচনা ও সারাংশ'
        ];

        $lessonType = $this->faker->randomElement(['video', 'text', 'pdf', 'audio']);
        
        return [
            'course_id' => Course::factory(),
            'module_id' => CourseModule::factory(),
            'title' => $this->faker->randomElement($lessons),
            'content' => $this->faker->paragraphs(4, true),
            'lesson_type' => $lessonType,
            'video_url' => $lessonType === 'video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
            'video_duration' => $lessonType === 'video' ? $this->faker->numberBetween(300, 3600) : null,
            'video_provider' => $lessonType === 'video' ? 'youtube' : null,
            'reading_time_minutes' => $lessonType === 'text' ? $this->faker->numberBetween(5, 30) : null,
            'attachments' => $this->faker->optional()->randomElement([
                json_encode(['file1.pdf', 'notes.docx']),
                json_encode(['handout.pdf']),
                null
            ]),
            'resources' => $this->faker->optional()->randomElement([
                json_encode(['https://example.com/resource1', 'https://example.com/resource2']),
                null
            ]),
            'primary_file_path' => $lessonType === 'pdf' ? 'lessons/sample.pdf' : null,
            'primary_file_type' => $lessonType === 'pdf' ? 'pdf' : null,
            'primary_file_size' => $lessonType === 'pdf' ? $this->faker->numberBetween(100000, 5000000) : null,
            'is_preview' => $this->faker->boolean(20), // 20% preview lessons
            'is_mandatory' => $this->faker->boolean(80), // 80% mandatory
            'requires_completion' => $this->faker->boolean(60),
            'minimum_time_seconds' => $this->faker->optional()->numberBetween(60, 1800),
            'sort_order' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }

    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_duration' => $this->faker->numberBetween(300, 3600),
            'video_provider' => 'youtube',
        ]);
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'lesson_type' => 'pdf',
            'primary_file_path' => 'lessons/sample.pdf',
            'primary_file_type' => 'pdf',
            'primary_file_size' => $this->faker->numberBetween(100000, 5000000),
        ]);
    }

    public function preview(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_preview' => true,
        ]);
    }
}