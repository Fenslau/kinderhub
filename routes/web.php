<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/redirect', [LoginController::class, 'redirectToProvider'])->name('login.redirect');
Route::get('/callback', [LoginController::class, 'handleProviderCallback'])->name('login.callback');
