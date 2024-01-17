<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRatingRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BookController extends Controller
{
    function bookList(Request $request) : View {
        //* params
        $listShown = $request->list_shown;
        $search = $request->search;

        //* validate list shown
        if ($listShown) {
            $isInvalid = ($listShown >= 10 && $listShown <= 100) == false;
            if ($isInvalid) {
                abort(Response::HTTP_FORBIDDEN, 'list shown input is invalid');
            }
        }

        //* get books
        $books = Book::query()
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

        //* return data
        return view('books.book-list')->with(compact('books'));
    }

    function famousAuthor() : View {
        //* get authors
        $authors = Author::query()
            ->orderBy('voters', 'desc')
            ->limit(10)
            ->get();

        //* return data
        return view('books.famous-author')->with(compact('authors'));
    }

    function insertRating() : View {
        $authors = Author::select('name', 'id')->whereHas('books')->get();
        return view('books.insert-rating')->with(compact('authors'));
    }

    function storeRating(StoreRatingRequest $request) : RedirectResponse {
        try {
            //* record db changes
            DB::beginTransaction();

            //* params
            $authorId = $request->author_id;
            $bookId = $request->book_id;

            //* check author is exist
            $author = Author::find($authorId);
            if ($author == null) {
                abort(Response::HTTP_NOT_FOUND, 'author not found');
            }

            //* check book is exist
            $book = Book::where('id', $bookId)->where('author_id', $authorId)->first();
            if ($book == null) {
                abort(Response::HTTP_NOT_FOUND, 'book not found');
            }

            //* store rating
            $this->_storeRating($request);

            //* update book ratings & voters
            $this->_updateBookAverageRatingAndVoters($bookId);

            //* update authors voters
            $this->_updateAuthorVoters($authorId);

            //* commit db changes
            DB::commit();

            //* return data
            return redirect(route('books.index'));
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
    }

    function getBooksByAuthor(Request $request) : JsonResponse {
        //* params
        $authorId = $request->author_id;

        //* check author is exist
        $author = Author::find($authorId);
        if ($author == null) {
            abort(Response::HTTP_NOT_FOUND, 'author not found');
        }

        //* check books is exist
        $books = Book::query()
            ->select('id', 'name')
            ->whereHas('author', function ($query) use($authorId) {
                $query->where('id', $authorId);
            })
            ->get();

        //* return data
        return response()->json([
            'status' => true,
            'message' => 'success get books list',
            'data' => (object)[
                'books' => $books,
            ],
        ], Response::HTTP_OK);
    }

    private function _storeRating(StoreRatingRequest $request) : Rating {
        $rating = Rating::create([
            'id' => Str::orderedUuid(),
            'book_id' => $request->book_id,
            'scale' => $request->scale,
        ]);

        return $rating;
    }

    private function _updateBookAverageRatingAndVoters (string $bookId) : Book {
        //* calculate book ratings
        $book = Book::find($bookId);
        $voters = $book->ratings->count();
        $averageRating = 0;
        if ($voters > 0) {
            $averageRating = $book->ratings->avg('scale');
        }

        //* update on book data
        $book->update([
            'voters' => $voters,
            'average_rating' => $averageRating,
        ]);

        //* return data
        return $book;
    }

    private function _updateAuthorVoters (string $authorId) : Author {
        //* calculate author
        $author = Author::find($authorId);
        $voters = $author->books->sum('voters');

        //* update author
        $author->update([
            'voters' => $voters,
        ]);

        //* return data
        return $author;
    }
}
