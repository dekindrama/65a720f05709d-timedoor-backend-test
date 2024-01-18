<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateAuthorAverageRatingAndVotersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::query()
            ->whereHas('books', function ($query) {
                $query->where('voters', '>', 0);
            })
            ->get();

        foreach ($authors as $author) {
            //* calculate author ratings
            $voters = $author->books->sum('voters');
            $averageRating = 0;
            if ($voters > 0) {
                $averageRating = $author
                    ->books
                    ->where('voters', '>', 0)
                    ->avg('average_rating');
            }

            //* update author
            $author->update([
                'voters' => $voters,
                'average_rating' => $averageRating,
            ]);
        }
    }
}
