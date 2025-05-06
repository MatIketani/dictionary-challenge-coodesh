<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Words\WordsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('auth')->group(function () {

    Route::post('signup', [AuthController::class, 'signUp']);

    Route::post('signin', [AuthController::class, 'signIn']);
});

Route::middleware('auth:api')->group(function () {

    Route::prefix('user/me')->group(function () {

        Route::get('/', [UsersController::class, 'me']);

        Route::get('/history', [UsersController::class, 'getHistory']);
    });

    Route::prefix('entries/en')->group(function () {

        Route::get('/', [WordsController::class, 'entries']);

        Route::get('/{word}', [WordsController::class, 'entry']);
    });
});
