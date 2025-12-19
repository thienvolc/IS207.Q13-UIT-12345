<?php

use App\Http\Controllers\Web\Sales\CartController;
use App\Http\Controllers\Web\Sales\OrderController;
use Illuminate\Support\Facades\Route;

// Cart Page (requires auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.page');
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.page');
    Route::get('/order/success', [CartController::class, 'orderSuccess'])->name('order.success');
});

// Cart API endpoints (for AJAX calls, requires auth)
Route::middleware(['auth'])->prefix('api/web')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.api.get');
    Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.api.add');
    Route::put('/cart/items/{id}', [CartController::class, 'updateQuantity'])->name('cart.api.update');
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem'])->name('cart.api.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.api.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.api.checkout');
});
