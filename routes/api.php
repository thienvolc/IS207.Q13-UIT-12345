<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Public;
use \App\Http\Controllers\Auth;
use \App\Http\Controllers\Me;
use \App\Http\Controllers\Admin;

$auth = \App\Http\Middleware\AuthMiddleware::class;

Route::prefix('products')->group(function () {
    Route::get('/', [Public\ProductController::class, 'search']);
    Route::get('/{product_id}/related', [Public\ProductController::class, 'related'])
        ->where('product_id', '[0-9]+');
    Route::get('/{product_id}', [Public\ProductController::class, 'show'])
        ->where('product_id', '[0-9]+');
    Route::get('/{slug}', [Public\ProductController::class, 'showBySlug']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [Public\CategoryController::class, 'index']);
    Route::get('/{slug}', [Public\CategoryController::class, 'show']);
    Route::get('/{slug}/products', [Public\CategoryController::class, 'products']);
});

Route::prefix('tags')->group(function () {
    Route::get('/{tag_id}/products', [Public\TagController::class, 'products']);
});

Route::prefix('users/auth')->group(function () use ($auth) {
    Route::post('register', [Auth\AuthController::class, 'register']);
    Route::post('login', [Auth\AuthController::class, 'login']);
    Route::post('logout', [Auth\AuthController::class, 'logout'])->middleware($auth);
    Route::post('forgot-password', [Auth\AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [Auth\AuthController::class, 'resetPassword']);
});

Route::middleware($auth)->prefix('me')->group(function () {
    // User profile
    Route::get('/', [Me\UserController::class, 'show']);
    Route::put('/', [Me\UserController::class, 'update']);
    Route::put('password', [Me\UserController::class, 'updatePassword']);

    // Cart
    Route::get('carts', [Me\CartController::class, 'index']);
    Route::post('carts/items', [Me\CartController::class, 'addItem']);
    Route::patch('carts/items/{cart_item_id}', [Me\CartController::class, 'updateItem']);
    Route::delete('carts/items/{cart_item_id}', [Me\CartController::class, 'deleteItem']);
    Route::delete('carts/clear', [Me\CartController::class, 'clearCart']);
    Route::patch('carts/checkout', [Me\CartController::class, 'checkout']);

    // Order
    Route::get('orders', [Me\OrderController::class, 'index']);
    Route::post('orders', [Me\OrderController::class, 'place']);
    Route::get('orders/{order_id}', [Me\OrderController::class, 'show']);
    Route::get('orders/{order_id}/status', [Me\OrderController::class, 'status']);
    Route::patch('orders/{order_id}/shipping', [Me\OrderController::class, 'updateShipping']);
    Route::delete('orders/{order_id}/cancel', [Me\OrderController::class, 'cancel']);
});

// Admin routes (pass role param)
Route::middleware($auth . ':admin')->prefix('admin')->group(function () {
    // Users
    Route::get('users', [Admin\UserController::class, 'index']);
    Route::post('users', [Admin\UserController::class, 'store']);
    Route::get('users/{user_id}', [Admin\UserController::class, 'show']);
    Route::delete('users/{user_id}', [Admin\UserController::class, 'destroy']);
    Route::patch('users/{user_id}/status', [Admin\UserController::class, 'updateStatus']);
    Route::patch('users/{user_id}/roles', [Admin\UserController::class, 'updateRoles']);

    // Orders
    Route::get('orders', [Admin\OrderController::class, 'index']);
    Route::get('orders/{order_id}', [Admin\OrderController::class, 'show']);
    Route::get('orders/{order_id}/status', [Admin\OrderController::class, 'status']);
    Route::patch('orders/{order_id}/status', [Admin\OrderController::class, 'updateStatus']);
    Route::delete('orders/{order_id}/cancel', [Admin\OrderController::class, 'cancel']);

    // Transactions
    Route::get('transactions', [Admin\TransactionController::class, 'index']);

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [Admin\ProductController::class, 'index']);
        Route::post('/', [Admin\ProductController::class, 'store']);
    Route::get('/{product_id}', [Admin\ProductController::class, 'show']);
    Route::put('/{product_id}', [Admin\ProductController::class, 'update']);
    Route::delete('/{product_id}', [Admin\ProductController::class, 'destroy']);

    Route::put('/{product_id}/categories', [Admin\ProductController::class, 'updateCategories']);
    Route::patch('/{product_id}/tags', [Admin\ProductController::class, 'updateTags']);
    Route::patch('/{product_id}/status', [Admin\ProductController::class, 'updateStatus']);

    Route::post('/{product_id}/metas', [Admin\ProductController::class, 'storeMeta']);
    Route::put('/{product_id}/metas/{meta_id}', [Admin\ProductController::class, 'updateMeta']);
    Route::delete('/{product_id}/metas/{meta_id}', [Admin\ProductController::class, 'destroyMeta']);

    Route::patch('/{product_id}/inventories', [Admin\ProductController::class, 'adjustInventory']);
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [Admin\CategoryController::class, 'index']);
        Route::post('/', [Admin\CategoryController::class, 'store']);
        Route::get('/{category_id}', [Admin\CategoryController::class, 'show']);
        Route::put('/{category_id}', [Admin\CategoryController::class, 'update']);
        Route::delete('/{category_id}', [Admin\CategoryController::class, 'destroy']);
    });

    // Tags
    Route::prefix('tags')->group(function () {
        Route::get('/', [Admin\TagController::class, 'index']);
        Route::post('/', [Admin\TagController::class, 'store']);
        Route::put('/{tag_id}', [Admin\TagController::class, 'update']);
        Route::delete('/{tag_id}', [Admin\TagController::class, 'destroy']);
    });
});
