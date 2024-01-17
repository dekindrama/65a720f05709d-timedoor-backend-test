<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateBookAverageRatingAndVotersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::whereHas('ratings')->get();
        foreach ($books as $book) {
            //* calculate book ratings
            $voters = $book->ratings->count();
            $averageRating = 0;
            if ($voters > 0) {
                $averageRating = $book->ratings->avg('scale');
            }

            //* update on book data
            $book->update([
                'voters' => $voters,
                'average_rating' => $averageRating,
            ]);
        }
    }
}
