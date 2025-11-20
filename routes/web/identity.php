<?php

use App\Http\Controllers\Web\Identity\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');

    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
});

// Route::middleware('auth');
Route::prefix('account')->group(function () {
    Route::get('/profile', fn() => view('pages.account.profile'))->name('account.profile');
    Route::get('/password', fn() => view('pages.account.password'))->name('account.password');
});

