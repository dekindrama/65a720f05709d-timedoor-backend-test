<?php

namespace App\Domains\Book;

use App\Domains\Book\Entities\UpdateAuthorAverageRatingAndVotersEntity as EntitiesUpdateAuthorAverageRatingAndVotersEntity;
use App\Domains\Book\Entities\UpdateBookAverageRatingAndVotersEntity;
use App\Exceptions\Commons\NotFoundException;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use App\UpdateAuthorAverageRatingAndVotersEntity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BookRepository implements BookRepositoryInterface
{
    private Book $_bookModel;
    private Author $_authorModel;
    private Rating $_ratingModel;
    public function __construct($bookModel, $authorModel, $ratingModel) {
        $this->_bookModel = $bookModel;
        $this->_authorModel = $authorModel;
        $this->_ratingModel = $ratingModel;
    }

    function getBooks(?int $listShown, ?string $search): Collection
    {
        $books = $this->_bookModel->query()
            ->when($listShown == null, function ($query) {
                return $query->limit(10);
            })
            ->when($listShown, function ($query) use($listShown) {
                return $query->limit($listShown);
            })
            ->when($search, function($query) use($search) {
                return $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('author', function($query) use($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            })
            ->orderBy('average_rating', 'desc')
            ->get();

        return $books;
    }

    function getFamousAuthors(): Collection
    {
        $authors = $this->_authorModel->query()
            ->orderBy('voters', 'desc')
            ->where('average_rating', '>', 5)
            ->limit(10)
            ->get();

        return $authors;
    }

    function getAuthors(): Collection
    {
        $authors = $this->_authorModel->select('name', 'id')->whereHas('books')->get();
        return $authors;
    }

    function storeRating(string $bookId, int $scale): Rating
    {
        $rating = $this->_ratingModel->create([
            'id' => Str::orderedUuid(),
            'book_id' => $bookId,
            'scale' => $scale,
        ]);

        return $rating;
    }

    function updateBookAverageRatingAndVoters(UpdateBookAverageRatingAndVotersEntity $params): Book
    {
        //* update on book data
        $book = $this->_bookModel->find($params->book_id);
        $book->update([
            'voters' => $params->voters,
            'average_rating' => $params->average_rating,
        ]);

        //* return data
        return $book;
    }

    function calculateBookAverageRatingAndVoters(string $bookId) : object {
        $book = $this->_bookModel->find($bookId);
        $voters = $book->ratings->count();
        $averageRating = 0;
        if ($voters > 0) {
            $averageRating = $book->ratings->avg('scale');
        }

        return (object)[
            'voters' => $voters,
            'average_rating' => $averageRating,
        ];
    }

    function updateAuthorAverageRatingAndVoters(EntitiesUpdateAuthorAverageRatingAndVotersEntity $params): Author
    {
        //* update author
        $author = $this->_authorModel->find($params->author_id);
        $author->update([
            'voters' => $params->voters,
            'average_rating' => $params->average_rating,
        ]);

        //* return data
        return $author;
    }

    function calculateAuthorAverageRatingAndVoters(string $authorId): object
    {
        //* calculate author
        $author = $this->_authorModel->find($authorId);
        $voters = $author->books->sum('voters');
        $averageRating = 0;
        if ($voters > 0) {
            $averageRating = $author
                ->books
                ->where('voters', '>', 0)
                ->avg('average_rating');
        }

        return (object)[
            'voters' => $voters,
            'average_rating' => $averageRating,
        ];
    }

    function checkAuthorIsExist(string $authorId): void
    {
        $author = $this->_authorModel->find($authorId);
        if ($author == null) {
            throw new NotFoundException('author not found');
        }
    }

    function checkAuthorBookIsExist(string $authorId, string $bookId): void
    {
        $book = $this->_bookModel->where('id', $bookId)->where('author_id', $authorId)->first();
        if ($book == null) {
            throw new NotFoundException('book not found');
        }
    }

    function getBooksByAuthor(string $authorId): Collection
    {
        $books = $this->_bookModel->query()
            ->select('id', 'name')
            ->whereHas('author', function ($query) use($authorId) {
                $query->where('id', $authorId);
            })
            ->get();

        return $books;
    }
}
