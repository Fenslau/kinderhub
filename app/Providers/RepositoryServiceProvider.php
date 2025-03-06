<?php

namespace App\Providers;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\ReadOnlyRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
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

        $this->app->when(CommentController::class)
            ->needs(CommentRepositoryInterface::class)
            ->give(function () {
                return new CommentRepository();
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
