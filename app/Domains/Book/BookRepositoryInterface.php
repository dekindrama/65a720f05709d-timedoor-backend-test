<?php

namespace App\Domains\Book;

use App\Domains\Book\Entities\UpdateBookAverageRatingAndVotersEntity;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface {
    public function getBooks(?int $listShown, ?string $search) : Collection;
    public function getFamousAuthors() : Collection;
    public function getAuthors(): Collection;
    public function storeRating(string $bookId, int $scale): Rating;
    public function updateBookAverageRatingAndVoters(UpdateBookAverageRatingAndVotersEntity $params): Book;
    public function updateAuthorVoters(string $authorId, int $voters): Author;
    public function checkAuthorIsExist(string $authorId) : void;
    public function checkAuthorBookIsExist(string $authorId, string $bookId) : void;
    public function getBooksByAuthor(string $authorId): Collection;

    function calculateBookAverageRatingAndVoters(string $bookId) : object;
    function calculateAuthorVoters(string $authorId) : object;
}
