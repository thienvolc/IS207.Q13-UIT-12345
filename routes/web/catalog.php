<?php

use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/san-pham')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/slug}', [ProductController::class, 'showBySlugView'])->name('products.show');
});
Route::get('/products/{slug}', [ProductController::class, 'showBySlugView']);
