<?php

namespace App\Providers;

use App\Http\Controllers\ArticleController;
use App\Repositories\ArticleRepository;
use App\Repositories\ReadOnlyRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(ArticleController::class)
            ->needs(ReadOnlyRepositoryInterface::class)
            ->give(function () {
                return new ArticleRepository();
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
