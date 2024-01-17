<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BookController extends Controller
{
    function bookList() : View {
        return view('books.book-list');
    }

    function famousAuthor() : View {
        return view('books.famous-author');
    }

    function insertRating() : View {
        return view('books.insert-rating');
    }

    function storeRating() : Redirector {
        return redirect(route('books.index'));
    }
}
