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

        for ($i=0; $i < 500000; $i++) {
            Rating::factory()->create([
                'book_id' => $bookIds->random(),
            ]);
        }
    }
}
