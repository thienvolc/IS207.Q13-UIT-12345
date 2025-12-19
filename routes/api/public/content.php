<?php

use App\Http\Controllers\Api\Public\Content\BlogController;
use Illuminate\Support\Facades\Route;

Route::prefix('blogs')->name('blogs.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});
