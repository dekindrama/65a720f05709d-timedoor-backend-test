<?php

namespace App\Providers\Services;

use App\Domains\Book\BookRepositoryInterface;
use App\Services\Book\BookService;
use App\Services\Book\BookServiceInterface;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
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
        $this->app->bind(BookServiceInterface::class, function($app) {
            return new BookService($app->make(BookRepositoryInterface::class));
        });
    }
}
