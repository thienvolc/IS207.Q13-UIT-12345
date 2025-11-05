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

// Public Category routes
Route::prefix('categories')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\CategoryController::class, 'index']);
    Route::get('/{slug}', [\App\Http\Controllers\Public\CategoryController::class, 'show']);
    Route::get('/{slug}/products', [\App\Http\Controllers\Public\CategoryController::class, 'products']);
});

// Public Tag routes
Route::prefix('tags')->group(function () {
    Route::get('/{tag_id}/products', [\App\Http\Controllers\Public\TagController::class, 'products']);
});

// Me (user) routes
Route::middleware($auth)->prefix('me')->group(function () {
    // Cart routes
    Route::get('carts', [\App\Http\Controllers\Me\CartController::class, 'index']);
    Route::post('carts/items', [\App\Http\Controllers\Me\CartController::class, 'addItem']);
    Route::patch('carts/items/{cart_item_id}', [\App\Http\Controllers\Me\CartController::class, 'updateItem']);
    Route::delete('carts/items/{cart_item_id}', [\App\Http\Controllers\Me\CartController::class, 'deleteItem']);
    Route::delete('carts/clear', [\App\Http\Controllers\Me\CartController::class, 'clearCart']);
    Route::patch('carts/checkout', [\App\Http\Controllers\Me\CartController::class, 'checkout']);

    // Order routes
    Route::get('orders', [\App\Http\Controllers\Me\OrderController::class, 'index']);
    Route::post('orders', [\App\Http\Controllers\Me\OrderController::class, 'place']);
    Route::get('orders/{id}', [\App\Http\Controllers\Me\OrderController::class, 'show']);
    Route::get('orders/{id}/status', [\App\Http\Controllers\Me\OrderController::class, 'status']);
    Route::patch('orders/{id}/shipping', [\App\Http\Controllers\Me\OrderController::class, 'updateShipping']);
    Route::delete('orders/{id}/cancel', [\App\Http\Controllers\Me\OrderController::class, 'cancel']);
});

// Admin routes (pass role param)
Route::middleware($auth.':admin')->prefix('admin')->group(function () {
    // Admin Orders
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index']);
    Route::get('orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show']);
    Route::get('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'status']);
    Route::patch('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus']);

    // Admin Transactions
    Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index']);

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

    // Admin Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::get('/{category_id}', [\App\Http\Controllers\Admin\CategoryController::class, 'show']);
        Route::put('/{category_id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('/{category_id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);
    });

    // Admin Tags
    Route::prefix('tags')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TagController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Admin\TagController::class, 'store']);
        Route::put('/{tag_id}', [\App\Http\Controllers\Admin\TagController::class, 'update']);
        Route::delete('/{tag_id}', [\App\Http\Controllers\Admin\TagController::class, 'destroy']);
    });
});
