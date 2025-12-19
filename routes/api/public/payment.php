<?php

use App\Http\Controllers\Api\Public\Payment\PayOSController;
use App\Http\Controllers\Api\Public\Payment\VNPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment/vnpay')->name('payment.vnpay.')->group(function () {
    Route::get('/ipn', [VNPayController::class, 'ipn'])->name('ipn');
    Route::get('/return', [VNPayController::class, 'return'])->name('return');
});

Route::prefix('payment/payos')->name('payment.payos.')->group(function () {
    Route::post('/webhook', [PayOSController::class, 'webhook'])->name('webhook');
    Route::get('/return', [PayOSController::class, 'return'])->name('return');
    Route::get('/cancel', [PayOSController::class, 'cancel'])->name('cancel');
});

