<?php

use App\Http\Controllers\Book\BookController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('books.index'));
});

route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'bookList'])->name('books.index');
    Route::get('/famous-author', [BookController::class, 'famousAuthor'])->name('books.famous-author');
    Route::get('/insert-rating', [BookController::class, 'insertRating'])->name('books.insert-rating');
    Route::post('/insert-rating', [BookController::class, 'storeRating'])->name('books.store-rating');
});
