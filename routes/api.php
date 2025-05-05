<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('auth')->group(function () {

    Route::post('signup', [AuthController::class, 'signUp']);

    Route::post('signin', [AuthController::class, 'signIn']);
});
