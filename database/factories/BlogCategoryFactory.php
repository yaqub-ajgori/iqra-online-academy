<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'ইসলামিক শিক্ষা',
            'কুরআন ও হাদিস',
            'ইসলামের ইতিহাস',
            'ইবাদত ও আমল',
            'ইসলামি জীবনযাত্রা',
            'দুআ ও যিকির',
            'ইসলামি সংস্কৃতি',
            'হালাল ও হারাম',
            'পারিবারিক জীবন',
            'যুব সমাজ'
        ];

        $name = fake()->unique()->randomElement($categories);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(8),
            'is_active' => true,
        ];
    }
}