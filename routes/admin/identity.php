<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('sign-out', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('forgot-password', [AdminAuthController::class, 'sendResetEmail'])->name('admin.password.email');
Route::get('reset-password', [AdminAuthController::class, 'showResetPasswordForm'])->name('admin.password.reset');
Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.update');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
