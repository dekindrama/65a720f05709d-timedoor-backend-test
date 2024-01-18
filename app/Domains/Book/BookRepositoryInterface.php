<?php

namespace App\Domains\Book;

use App\Domains\Book\Entities\UpdateAuthorAverageRatingAndVotersEntity;
use App\Domains\Book\Entities\UpdateBookAverageRatingAndVotersEntity;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface {
    function getBooks(?int $listShown, ?string $search) : Collection;
    function getFamousAuthors() : Collection;
    function getAuthors(): Collection;
    function storeRating(string $bookId, int $scale): Rating;
    function updateBookAverageRatingAndVoters(UpdateBookAverageRatingAndVotersEntity $params): Book;
    function updateAuthorAverageRatingAndVoters(UpdateAuthorAverageRatingAndVotersEntity $params): Author;
    function checkAuthorIsExist(string $authorId) : void;
    function checkAuthorBookIsExist(string $authorId, string $bookId) : void;
    function getBooksByAuthor(string $authorId): Collection;

    function calculateBookAverageRatingAndVoters(string $bookId) : object;
    function calculateAuthorAverageRatingAndVoters(string $authorId) : object;
}
