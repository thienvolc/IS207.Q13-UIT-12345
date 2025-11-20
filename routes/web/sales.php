<?php

use App\Http\Controllers\Web\Sales\CartController;
use App\Http\Controllers\Web\Sales\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware([])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.page');
    Route::get('/cart', fn() => view('pages.cart'))->name('cart.page');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
});
