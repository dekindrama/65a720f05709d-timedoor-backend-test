<?php

namespace App\Http\Controllers\Book;

use App\Exceptions\Commons\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRatingRequest;
use App\Services\Book\BookServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    function bookList(Request $request, BookServiceInterface $bookService) : View {
        try {
            //* params
            $listShown = $request->list_shown;
            $search = $request->search;

            //* get books
            $books = $bookService->getBooks($listShown, $search);

            //* return data
            return view('books.book-list')->with(compact('books'));
        } catch (CommonException $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            $th->throwAbort();
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
    }

    function famousAuthor(BookServiceInterface $bookService) : View {
        try {
            //* get authors
            $authors = $bookService->getFamousAuthors();

            //* return data
            return view('books.famous-author')->with(compact('authors'));
        } catch (CommonException $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            $th->throwAbort();
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
    }

    function insertRating(BookServiceInterface $bookService) : View {
        try {
            $authors = $bookService->getAuthors();
            return view('books.insert-rating')->with(compact('authors'));
        } catch (CommonException $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            $th->throwAbort();
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
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
        } catch (CommonException $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            $th->throwAbort();
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
    }

    function getBooksByAuthor(Request $request, BookServiceInterface $bookService) : Response {
        try {
            //* params
            $authorId = $request->author_id;

            //* check books is exist
            $books = $bookService->getBooksByAuthor($authorId);

            //* return data
            return response([
                'status' => true,
                'message' => 'success get books list',
                'data' => (object)[
                    'books' => $books,
                ],
            ], Response::HTTP_OK);
        } catch (CommonException $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            $th->throwAbort();
        } catch (\Throwable $th) {
            //* rollback db changes
            DB::rollBack();

            //* throw error
            throw $th;
        }
    }
}
