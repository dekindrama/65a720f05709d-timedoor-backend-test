<?php

namespace App\Providers\Domains;

use App\Domains\Book\BookRepository;
use App\Domains\Book\BookRepositoryInterface;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Support\ServiceProvider;

class BookDomainProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(BookRepositoryInterface::class, function ($app) {
            return new BookRepository(
                new Book(),
                new Author(),
                new Rating(),
            );
        });
    }
}
