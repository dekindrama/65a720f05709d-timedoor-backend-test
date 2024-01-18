<?php

namespace Tests\Feature\Services\Book;

use App\Domains\Book\BookRepositoryInterface;
use App\Exceptions\Commons\NotFoundException;
use App\Http\Requests\Book\StoreRatingRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use App\Services\Book\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    function test_get_books() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBooks = Book::factory(10)->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $books = $service->getBooks(null, null);

        //* assert
        $this->assertCount(10, $books);
    }

    function test_get_books_with_filter() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBooks = Book::factory(10)->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
            'name' => 'test book',
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $books = $service->getBooks(100, 'test book');

        //* assert
        $this->assertCount(10, $books);
    }

    function test_get_famous_authors() : void {
        //* params
        $fakeAuthor1 = Author::factory()->create([
            'voters' => 3,
        ]);
        $fakeAuthor2 = Author::factory()->create([
            'voters' => 2,
        ]);
        $fakeAuthor3 = Author::factory()->create([
            'voters' => 1,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $authors = $service->getFamousAuthors();

        //* assert
        $this->assertEquals($authors->pluck('voters')->toArray(), [3, 2, 1]);
    }

    function test_get_books_by_author() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $books = $service->getBooksByAuthor($fakeAuthor->id);

        //* assert
        $this->assertCount(1, $books);
    }

    function test_get_books_by_author_case_not_found_author() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));

        //* assert
        $this->assertThrows(
            fn() => $service->getBooksByAuthor('test'),
            NotFoundException::class
        );
    }

    function test_store_rating() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);
        $validatedRequest = new StoreRatingRequest([
            'author_id' => $fakeAuthor->id,
            'book_id' => $fakeBook->id,
            'scale' => 9,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $storedRating = $service->storeRating($validatedRequest);

        //* assert
        $this->assertDatabaseCount(Rating::class, 1);
        $this->assertDatabaseHas(Rating::class, [
            'book_id' => $fakeBook->id,
            'scale' => 9,
        ]);
        $this->assertDatabaseHas(Book::class, [
            'id' => $fakeBook->id,
            'voters' => 1,
            'average_rating' => 9,
        ]);
        $this->assertDatabaseHas(Author::class, [
            'id' => $fakeAuthor->id,
            'voters' => 1,
        ]);
    }

    function test_store_rating_case_not_found_author() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);
        $validatedRequest = new StoreRatingRequest([
            'author_id' => 'test',
            'book_id' => $fakeBook->id,
            'scale' => 9,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));

        //* assert
        $this->assertThrows(fn() => $service->storeRating($validatedRequest), NotFoundException::class);
    }

    function test_store_rating_case_not_found_book() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);
        $validatedRequest = new StoreRatingRequest([
            'author_id' => $fakeAuthor->id,
            'book_id' => 'test',
            'scale' => 9,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));

        //* assert
        $this->assertThrows(fn() => $service->storeRating($validatedRequest), NotFoundException::class);
    }

    function test_get_authors() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $service = new BookService(app()->make(BookRepositoryInterface::class));
        $authors = $service->getAuthors();

        //* assert
        $this->assertCount(1, $authors);
    }
}
