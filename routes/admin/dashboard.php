<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Api\Admin\Catalog\ProductAdminController;
use App\Http\Controllers\Api\Admin\Catalog\TagAdminController;
use App\Http\Controllers\Api\Admin\Identity\UserAdminController;
use App\Http\Controllers\Api\Admin\Sales\OrderAdminController;

Route::name('admin.')
    // ->middleware(['auth','is_admin'])   // dùng khi BE đã có middleware is_admin
    ->middleware([]) // tạm bỏ middleware để FE dev (thay bằng ['auth','is_admin'] khi backend sẵn sàng)
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Nếu bạn đã có ProductController, OrderController... ở App\Http\Controllers\Admin
        Route::resource('products', ProductAdminController::class);
        Route::resource('orders', OrderAdminController::class)->only(['index', 'show', 'update']);
        Route::resource('customers', UserAdminController::class)->only(['index', 'show']);
        Route::resource('posts', TagAdminController::class);
    });

