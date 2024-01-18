<?php

namespace App\Domains\Book\Entities;

class UpdateBookAverageRatingAndVotersEntity
{
    public string $book_id;
    public int $voters;
    public float $average_rating;
    public function __construct(string $bookId, int $voters, float $averageRating) {
        $this->book_id = $bookId;
        $this->voters = $voters;
        $this->average_rating = $averageRating;

        return $this;
    }
}
