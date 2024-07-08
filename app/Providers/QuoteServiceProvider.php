<?php

namespace App\Providers;

use App\Repositories\KanyeQuotes;
use App\Repositories\QuoteRepositoryInterface;
use App\Services\QuoteService;
use Illuminate\Support\ServiceProvider;

class QuoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            QuoteRepositoryInterface::class,
            KanyeQuotes::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
