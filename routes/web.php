<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/redirect', [LoginController::class, 'redirectToProvider'])->name('login.redirect');
Route::get('/callback', [LoginController::class, 'handleProviderCallback'])->name('login.callback');

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::group([
    'prefix' => 'articles',
    'as' => 'articles.'
], function ($router) {
    $router->get('', [ArticleController::class, 'index'])->name('index');
    $router->get('{slug}', [ArticleController::class, 'show'])->name('show');
});

Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function ($router) {
    $router->get('', [UserController::class, 'index'])->name('index');
    $router->get('{user}', [UserController::class, 'show'])->name('show');
});

Route::group([
    'prefix' => 'cities',
    'as' => 'cities.'
], function ($router) {
    $router->get('', [CityController::class, 'index'])->name('index');
    $router->get('{city}', [CityController::class, 'show'])->name('show');
});

Route::group([
    'prefix' => 'comments',
    'as' => 'comments.'
], function ($router) {
    $router->post('', [CommentController::class, 'store'])->name('store')
        ->middleware(['auth', 'verified']);
});
