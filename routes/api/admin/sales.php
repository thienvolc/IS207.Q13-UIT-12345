<?php


use App\Http\Controllers\Api\Admin\Sales\OrderAdminController;
use App\Http\Controllers\Api\Admin\Sales\TransactionAdminController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

Route::prefix('admin/orders')->middleware($auth . ':admin')->controller(OrderAdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{order_id}', 'show');
    Route::get('{order_id}/status', 'status');
    Route::patch('{order_id}/status', 'updateStatus');
    Route::delete('{order_id}/cancel', 'cancel');
});

Route::prefix('admin/transactions')->middleware($auth . ':admin')->controller(TransactionAdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{trans_id}', 'show');
});
