<?php

namespace App\Services\Book;

use App\Http\Requests\Book\StoreRatingRequest;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface {
    public function getBooks(?int $listShown, ?string $search) : Collection;
    public function getFamousAuthors() : Collection;
    public function storeRating(StoreRatingRequest $request) : Rating;
    public function getBooksByAuthor(string $authorId) : Collection;
}
