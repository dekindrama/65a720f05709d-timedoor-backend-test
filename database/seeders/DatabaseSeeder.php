<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');

        //* 1000 fakes author
        //* 3000 fakes book category
        //* 100.000 fakes books
        //* 500.000 fakes rating

        $this->call([
            AuthorSeeder::class,
            BookCategorySeeder::class,
            BookSeeder::class,
            RatingSeeder::class,
            UpdateBookAverageRatingAndVotersSeeder::class,
            UpdateAuthorAverageRatingAndVotersSeeder::class,
        ]);
    }
}
