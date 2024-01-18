<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRatingRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use App\Services\Book\BookService;
use App\Services\Book\BookServiceInterface;
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
    function bookList(Request $request, BookServiceInterface $bookService) : View {
        //* params
        $listShown = $request->list_shown;
        $search = $request->search;

        //* get books
        $books = $bookService->getBooks($listShown, $search);

        //* return data
        return view('books.book-list')->with(compact('books'));
    }

    function famousAuthor(BookServiceInterface $bookService) : View {
        //* get authors
        $authors = $bookService->getFamousAuthors();

        //* return data
        return view('books.famous-author')->with(compact('authors'));
    }

    function insertRating() : View {
        $authors = Author::select('name', 'id')->whereHas('books')->get();
        return view('books.insert-rating')->with(compact('authors'));
    }

    function storeRating(StoreRatingRequest $request, BookServiceInterface $bookService) : RedirectResponse {
        try {
            //* record db changes
            DB::beginTransaction();

            //* store rating
            $bookService->storeRating($request);

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

    function getBooksByAuthor(Request $request, BookServiceInterface $bookService) : JsonResponse {
        //* params
        $authorId = $request->author_id;

        //* check books is exist
        $books = $bookService->getBooksByAuthor($authorId);

        //* return data
        return response()->json([
            'status' => true,
            'message' => 'success get books list',
            'data' => (object)[
                'books' => $books,
            ],
        ], Response::HTTP_OK);
    }
}
