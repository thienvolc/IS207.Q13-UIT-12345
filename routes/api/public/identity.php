<?php

use App\Http\Controllers\Api\Public\Identity\AuthPublicController;
use App\Http\Controllers\Api\Public\Identity\UserProfileController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AccountController;

$auth = AuthMiddleware::class;

Route::prefix("auth")->controller(AuthPublicController::class)->group(function () use ($auth) {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware($auth);
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

Route::prefix('me')->middleware($auth)->controller(UserProfileController::class)->group(function () {
    Route::get('/', 'show');
    Route::put('/', 'update');
    Route::put('password', 'updatePassword');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
});
