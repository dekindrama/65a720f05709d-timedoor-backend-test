<?php

namespace Tests\Feature\Domains\Book;

use App\Domains\Book\BookRepository;
use App\Domains\Book\Entities\UpdateBookAverageRatingAndVotersEntity;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
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
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $books = $repository->getBooks(null, null);

        //* assert
        $this->assertCount($fakeBooks->count(), $books);
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
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $authors = $repository->getFamousAuthors();

        //* assert
        $this->assertEquals($authors->pluck('voters')->toArray(), [3, 2, 1]);
    }

    function test_get_authors() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBooks = Book::factory(1)->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);
        $fakeAuthor2 = Author::factory()->create();
        $fakeBooks2 = Book::factory(1)->create([
            'author_id' => $fakeAuthor2->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $authors = $repository->getAuthors();

        //* assert
        $this->assertCount(2, $authors);
    }

    function test_store_rating() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $storedRating = $repository->storeRating($fakeBook->id, 9);

        //* assert
        $this->assertDatabaseCount(Rating::class, 1);
        $this->assertDatabaseHas(Rating::class, [
            'book_id' => $storedRating->book_id,
            'scale' => $storedRating->scale,
        ]);
    }

    function test_update_book_average_rating_and_voters() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $entity = new UpdateBookAverageRatingAndVotersEntity(
            $fakeBook->id,
            5,
            9,
        );
        $repository->updateBookAverageRatingAndVoters($entity);

        //* assert
        $this->assertDatabaseHas(Book::class, [
            'id' => $fakeBook->id,
            'voters' => 5,
            'average_rating' => 9,
        ]);
    }

    function test_update_author_voters() : void {
        //* params
        $fakeAuthor = Author::factory()->create();

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $repository->updateAuthorVoters($fakeAuthor->id, 5);

        //* assert
        $this->assertDatabaseHas(Author::class, [
            'id' => $fakeAuthor->id,
            'voters' => 5
        ]);
    }

    function test_calculate_book_average_rating_and_voters() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);
        $fakeRating = Rating::factory(10)->create([
            'book_id' => $fakeBook->id,
            'scale' => 9,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $bookRating = $repository->calculateBookAverageRatingAndVoters($fakeBook->id);

        //* assert
        $this->assertEquals((object)[
            'voters' => 10,
            'average_rating' => 9,
        ], $bookRating);
    }

    function test_calculate_author_voters() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory(3)->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
            'average_rating' => 9,
            'voters' => 10,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $authorRating = $repository->calculateAuthorVoters($fakeAuthor->id);

        //* assert
        $this->assertEquals((object)[
            'voters' => 30, //* 3 fake books, 10 voters on each book
        ], $authorRating);
    }

    function test_check_author_is_exist() : void {
        //* params
        $fakeAuthor = Author::factory()->create();

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $repository->checkAuthorIsExist($fakeAuthor->id);

        //* assert
        $this->expectNotToPerformAssertions();
    }

    function test_check_author_book_is_exist() : void {
        //* params
        $fakeBookCategory = BookCategory::factory()->create();
        $fakeAuthor = Author::factory()->create();
        $fakeBook = Book::factory()->create([
            'author_id' => $fakeAuthor->id,
            'book_category_id' => $fakeBookCategory->id,
        ]);

        //* action
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $storedRating = $repository->checkAuthorBookIsExist($fakeAuthor->id, $fakeBook->id);

        //* assert
        $this->expectNotToPerformAssertions();
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
        $repository = new BookRepository(new Book(), new Author(), new Rating());
        $books = $repository->getBooksByAuthor($fakeAuthor->id);

        //* assert
        $this->assertCount(1, $books);
    }
}
