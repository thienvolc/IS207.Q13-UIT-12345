<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPublicController;
use App\Http\Controllers\Api\PublicCategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserCartController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserOrderController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductPublicController::class, 'search']);
    Route::get('/{product_id}/related', [ProductPublicController::class, 'related'])->where('product_id', '[0-9]+');
    Route::get('/{product_id}', [ProductPublicController::class, 'show'])->where('product_id', '[0-9]+');
    Route::get('/{slug}', [ProductPublicController::class, 'showBySlug']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [PublicCategoryController::class, 'index']);
    Route::get('/{slug}', [PublicCategoryController::class, 'show']);
    Route::get('/{slug}/products', [ProductPublicController::class, 'searchByCategorySlug']);
});

Route::prefix('tags')->group(function () {
    Route::get('/{tag_id}/products', [ProductPublicController::class, 'searchByTagId']);
});

Route::prefix('users/auth')->group(function () use ($auth) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware($auth);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware($auth)->prefix('me')->group(function () {
    Route::get('/', [UserProfileController::class, 'show']);
    Route::put('/', [UserProfileController::class, 'update']);
    Route::put('password', [UserProfileController::class, 'updatePassword']);

    Route::get('carts', [UserCartController::class, 'index']);
    Route::post('carts/items', [UserCartController::class, 'addItem']);
    Route::patch('carts/items/{cart_item_id}', [UserCartController::class, 'updateItem']);
    Route::delete('carts/items/{cart_item_id}', [UserCartController::class, 'deleteItem']);
    Route::delete('carts/clear', [UserCartController::class, 'clearCart']);
    Route::patch('carts/checkout', [UserCartController::class, 'checkout']);

    Route::get('orders', [UserOrderController::class, 'index']);
    Route::post('orders', [UserOrderController::class, 'place']);
    Route::get('orders/{order_id}', [UserOrderController::class, 'show']);
    Route::get('orders/{order_id}/status', [UserOrderController::class, 'status']);
    Route::patch('orders/{order_id}/shipping', [UserOrderController::class, 'updateShipping']);
    Route::delete('orders/{order_id}/cancel', [UserOrderController::class, 'cancel']);
});

Route::middleware($auth . ':admin')->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{user_id}', [UserController::class, 'show']);
    Route::delete('users/{user_id}', [UserController::class, 'destroy']);
    Route::patch('users/{user_id}/status', [UserController::class, 'updateStatus']);
    Route::patch('users/{user_id}/roles', [UserController::class, 'updateRoles']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order_id}', [OrderController::class, 'show']);
    Route::get('orders/{order_id}/status', [OrderController::class, 'status']);
    Route::patch('orders/{order_id}/status', [OrderController::class, 'updateStatus']);
    Route::delete('orders/{order_id}/cancel', [OrderController::class, 'cancel']);

    Route::get('transactions', [TransactionController::class, 'index']);

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product_id}', [ProductController::class, 'show']);
        Route::put('/{product_id}', [ProductController::class, 'update']);
        Route::delete('/{product_id}', [ProductController::class, 'destroy']);

        Route::put('/{product_id}/categories', [ProductController::class, 'updateCategories']);
        Route::patch('/{product_id}/tags', [ProductController::class, 'updateTags']);
        Route::patch('/{product_id}/status', [ProductController::class, 'updateStatus']);

        Route::post('/{product_id}/metas', [ProductController::class, 'storeMeta']);
        Route::put('/{product_id}/metas/{meta_id}', [ProductController::class, 'updateMeta']);
        Route::delete('/{product_id}/metas/{meta_id}', [ProductController::class, 'destroyMeta']);

        Route::patch('/{product_id}/inventories', [ProductController::class, 'adjustInventory']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{category_id}', [CategoryController::class, 'show']);
        Route::put('/{category_id}', [CategoryController::class, 'update']);
        Route::delete('/{category_id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
        Route::put('/{tag_id}', [TagController::class, 'update']);
        Route::delete('/{tag_id}', [TagController::class, 'destroy']);
    });
});
