<?php

namespace App\Services\Book;

use App\Domains\Book\BookRepository;
use App\Domains\Book\Entities\UpdateBookAverageRatingAndVotersEntity;
use App\Exceptions\Commons\BadRequestException;
use App\Http\Requests\Book\StoreRatingRequest;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;

class BookService implements BookServiceInterface
{
    private BookRepository $_bookRepository;
    public function __construct($bookRepository) {
        $this->_bookRepository = $bookRepository;
    }

    function getBooks(?int $listShown, ?string $search): Collection
    {
        //* validate list shown
        $this->_validateListShown($listShown);

        //* get books
        $books = $this->_bookRepository->getBooks($listShown, $search);

        //* return data
        return $books;
    }

    function getFamousAuthors(): Collection
    {
        $authors = $this->_bookRepository->getFamousAuthors();
        return $authors;
    }

    function storeRating(StoreRatingRequest $request): Rating
    {
        //* params
        $authorId = $request->author_id;
        $bookId = $request->book_id;
        $scale = $request->scale;

        //* check author is exist
        $this->_bookRepository->checkAuthorIsExist($authorId);

        //* check books is exist
        $this->_bookRepository->checkAuthorBookIsExist($authorId, $bookId);

        //* store rating
        $rating = $this->_bookRepository->storeRating($bookId, $scale);

        //* calculate book ratings & voters
        $bookRating = $this->_bookRepository->calculateBookAverageRatingAndVoters($bookId);

        //* update book ratings & voters
        $updateBookEntity = new UpdateBookAverageRatingAndVotersEntity($bookId, $bookRating->voters, $bookRating->average_rating);
        $this->_bookRepository->updateBookAverageRatingAndVoters($updateBookEntity);

        //* calculate author voters
        $authorRating = $this->_bookRepository->calculateAuthorVoters($authorId);

        //* update author voters
        $this->_bookRepository->updateAuthorVoters($authorId, $authorRating->voters);

        //* return data
        return $rating;
    }

    function getBooksByAuthor(string $authorId): Collection
    {
        //* check author is exist
        $this->_bookRepository->checkAuthorIsExist($authorId);

        //* get data
        $books = $this->_bookRepository->getBooksByAuthor($authorId);

        //* return data
        return $books;
    }


    private function _validateListShown(?int $listShown) : void {
        if ($listShown) {
            $isInvalid = ($listShown >= 10 && $listShown <= 100) == false;
            if ($isInvalid) {
                throw new BadRequestException('list shown input is invalid');
            }
        }
    }
}
