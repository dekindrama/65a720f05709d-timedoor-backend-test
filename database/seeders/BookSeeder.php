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

        Book::factory(100000)->create([
            'book_category_id' => $bookCategoryIds[0],
            'author_id' => $authorIds[0],
        ]);
    }
}
