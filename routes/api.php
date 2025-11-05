<?php

use Illuminate\Support\Facades\Route;

$apiAuth = \App\Http\Middleware\ApiTokenAuth::class;

// Me (user) routes
Route::middleware($apiAuth)->prefix('me')->group(function () {
    Route::get('carts', [\App\Http\Controllers\Me\CartController::class, 'index']);
    Route::post('carts/items', [\App\Http\Controllers\Me\CartController::class, 'addItem']);
    Route::post('orders', [\App\Http\Controllers\Me\OrderController::class, 'place']);
    Route::get('orders/{id}/status', [\App\Http\Controllers\Me\OrderController::class, 'status']);
});

// Admin routes (pass role param)
Route::middleware($apiAuth.':admin')->prefix('admin')->group(function () {
    Route::get('orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'status']);
    Route::put('orders/{id}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm']);
});
