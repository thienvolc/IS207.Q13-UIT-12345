<?php

use App\Http\Controllers\Api\Public\Content\BlogController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

// Public blog routes (no auth required)
Route::prefix('blogs')->name('blogs.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Authenticated user blog routes
Route::prefix('me/blogs')->middleware($auth)->name('me.blogs.')->group(function () {
    Route::get('/', [BlogController::class, 'myPosts'])->name('index');
    Route::post('/', [BlogController::class, 'store'])->name('store');
});
