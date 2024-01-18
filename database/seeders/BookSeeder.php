<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorIds = Author::inRandomOrder()->take(10)->pluck('id');
        $bookCategoryIds = BookCategory::inRandomOrder()->take(10)->pluck('id');

        for ($i=0; $i < 100000; $i++) {
            Book::factory()->create([
                'book_category_id' => $bookCategoryIds->random(),
                'author_id' => $authorIds->random(),
            ]);
        }
    }
}
