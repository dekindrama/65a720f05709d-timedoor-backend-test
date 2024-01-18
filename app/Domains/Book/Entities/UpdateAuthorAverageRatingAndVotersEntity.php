<?php

namespace App\Domains\Book\Entities;

class UpdateAuthorAverageRatingAndVotersEntity
{
    public string $author_id;
    public int $voters;
    public float $average_rating;
    public function __construct(string $authorId, int $voters, float $averageRating) {
        $this->author_id = $authorId;
        $this->voters = $voters;
        $this->average_rating = $averageRating;

        return $this;
    }
}
