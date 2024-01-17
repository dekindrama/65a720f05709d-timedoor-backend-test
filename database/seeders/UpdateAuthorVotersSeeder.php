<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateAuthorVotersSeeder extends Seeder
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
            //* calculate voters
            $voters = $author->books->sum('voters');

            //* update author
            $author->update([
                'voters' => $voters,
            ]);
        }
    }
}
