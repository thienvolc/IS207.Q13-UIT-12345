<?php

use Illuminate\Support\Facades\Route;

$auth = \App\Http\Middleware\AuthMiddleware::class;

// Public Product routes
Route::prefix('products')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\ProductController::class, 'search']);
    Route::get('/{id}/related', [\App\Http\Controllers\Public\ProductController::class, 'related'])->where('id', '[0-9]+');
    Route::get('/{id}', [\App\Http\Controllers\Public\ProductController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/{slug}', [\App\Http\Controllers\Public\ProductController::class, 'showBySlug']);
});

// Me (user) routes
Route::middleware($auth)->prefix('me')->group(function () {
    Route::get('carts', [\App\Http\Controllers\Me\CartController::class, 'index']);
    Route::post('carts/items', [\App\Http\Controllers\Me\CartController::class, 'addItem']);
    Route::post('orders', [\App\Http\Controllers\Me\OrderController::class, 'place']);
    Route::get('orders/{id}/status', [\App\Http\Controllers\Me\OrderController::class, 'status']);
});

// Admin routes (pass role param)
Route::middleware($auth.':admin')->prefix('admin')->group(function () {
    // Admin Orders
    Route::get('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'status']);
    Route::put('orders/{id}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm']);

    // Admin Products
    Route::prefix('products')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy']);

        Route::put('/{id}/categories', [\App\Http\Controllers\Admin\ProductController::class, 'updateCategories']);
        Route::patch('/{id}/tags', [\App\Http\Controllers\Admin\ProductController::class, 'updateTags']);
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\ProductController::class, 'updateStatus']);

        Route::post('/{id}/metas', [\App\Http\Controllers\Admin\ProductController::class, 'storeMeta']);
        Route::put('/{id}/metas/{metaId}', [\App\Http\Controllers\Admin\ProductController::class, 'updateMeta']);
        Route::delete('/{id}/metas/{metaId}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyMeta']);

        Route::patch('/{id}/inventories', [\App\Http\Controllers\Admin\ProductController::class, 'adjustInventory']);
    });
});
