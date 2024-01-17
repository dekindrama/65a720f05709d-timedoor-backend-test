<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::orderedUuid(),
            'name' => fake()->sentence,
            'book_category_id' => Str::orderedUuid(),
            'author_id' => Str::orderedUuid(),
            'rating' => rand(1, 10),
        ];
    }
}