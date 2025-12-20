<?php

use App\Http\Controllers\Web\Identity\AuthController;
use Illuminate\Support\Facades\Route;

// Routes cho guest (chưa đăng nhập)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');

    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');

    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
});

// Route logout cần đăng nhập
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes cho user đã đăng nhập
Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/profile', fn() => view('pages.account.profile'))->name('account.profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/password', fn() => view('pages.account.password'))->name('account.password');
    Route::post('/password', [AuthController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/orders', [\App\Http\Controllers\Web\Identity\OrderController::class, 'index'])->name('account.orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\Web\Identity\OrderController::class, 'show'])->name('account.orders.show');
    Route::get('/orders/{id}/repay', [\App\Http\Controllers\Web\Identity\OrderController::class, 'repay'])->name('account.orders.repay');
    Route::get('/my-posts', fn() => view('pages.account.my-posts'))->name('account.my-posts');
});
