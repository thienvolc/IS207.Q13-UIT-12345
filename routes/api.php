<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // <-- Thêm dòng này
use App\Http\Controllers\Api\Me\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/signup', [AuthController::class, 'signup'])->name('api.signup');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected routes (yêu cầu token)
Route::middleware('auth.api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // (Các route profile, cart... sẽ được thêm vào đây)
    Route::get('/me/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/me/profile', [ProfileController::class, 'update'])->name('api.profile.update');
});