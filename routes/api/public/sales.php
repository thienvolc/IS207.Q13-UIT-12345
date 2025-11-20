<?php

use App\Http\Controllers\Api\Public\Sales\CartController;
use App\Http\Controllers\Api\Public\Sales\CheckoutController;
use App\Http\Controllers\Api\Public\Sales\UserOrderController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

Route::prefix('me/carts')->middleware($auth)->controller(CartController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('items', 'addItem');
    Route::delete('items/{cart_item_id}', 'removeItem');
    Route::delete('clear', 'clearCart');
});

Route::prefix('me/carts')->controller(CheckoutController::class)->middleware($auth)->group(function () {
    Route::post('checkout', 'checkout');
});

Route::prefix('me/orders')->controller(UserOrderController::class)->middleware($auth)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'place');
    Route::get('{order_id}', 'show');
    Route::get('{order_id}/status', 'status');
    Route::patch('{order_id}/shipping', 'updateShipping');
    Route::delete('{order_id}/cancel', 'cancel');
});
