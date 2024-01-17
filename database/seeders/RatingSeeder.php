<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookIds = Book::inRandomOrder()->take(10)->pluck('id');

        Rating::factory(500000)->create([
            'book_id' => $bookIds[0],
        ]);
    }
}
