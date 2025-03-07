<?php

namespace App\Providers;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Repositories\ArticleRepository;
use App\Repositories\CityRepository;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\ReadOnlyRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\UserRepository;
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

        $this->app->when(UserController::class)
            ->needs(ReadOnlyRepositoryInterface::class)
            ->give(function () {
                return new UserRepository();
            });

        $this->app->when(CityController::class)
            ->needs(ReadOnlyRepositoryInterface::class)
            ->give(function () {
                return new CityRepository();
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
