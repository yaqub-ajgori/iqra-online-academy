<?php

namespace Database\Factories;

use App\Models\BlogTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogTag>
 */
class BlogTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = [
            'কুরআন', 'হাদিস', 'নামাজ', 'রোজা', 'হজ্জ', 'যাকাত', 'দুআ', 'যিকির',
            'তাওবা', 'সবর', 'শুকর', 'তাকওয়া', 'ইমান', 'আকিদা', 'ফিকহ', 'তাফসীর',
            'সুন্নাহ', 'হালাল', 'হারাম', 'ইসলামি পরিবার', 'যুব সমাজ', 'শিক্ষা',
            'আরবি', 'হিজাব', 'দাওয়াত', 'জিহাদ', 'আখেরাত', 'জান্নাত', 'জাহান্নাম'
        ];

        $name = fake()->unique()->randomElement($tags);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}