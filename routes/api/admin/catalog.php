<?php

use App\Http\Controllers\Api\Admin\Catalog\CategoryAdminController;
use App\Http\Controllers\Api\Admin\Catalog\ProductAdminController;
use App\Http\Controllers\Api\Admin\Catalog\TagAdminController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

Route::prefix('admin/products')->middleware($auth . ':admin')->controller(ProductAdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{product_id}', 'show');
    Route::put('/{product_id}', 'update');
    Route::delete('/{product_id}', 'destroy');

    Route::put('/{product_id}/categories', 'updateCategories');
    Route::patch('/{product_id}/tags', 'updateTags');
    Route::patch('/{product_id}/status', 'updateStatus');

    Route::post('/{product_id}/metas', 'storeMeta');
    Route::put('/{product_id}/metas/{meta_id}', 'updateMeta');
    Route::delete('/{product_id}/metas/{meta_id}', 'destroyMeta');

    Route::patch('/{product_id}/inventories', 'adjustInventory');
});

Route::prefix('admin/categories')->middleware($auth . ':admin')->controller(CategoryAdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{category_id}', 'show');
    Route::put('/{category_id}', 'update');
    Route::delete('/{category_id}', 'destroy');
});

Route::prefix('admin/tags')->middleware($auth . ':admin')->group(function () {
    Route::get('/', [TagAdminController::class, 'searchAdmin']);
    Route::post('/', [TagAdminController::class, 'store']);
    Route::put('/{tag_id}', [TagAdminController::class, 'update']);
    Route::delete('/{tag_id}', [TagAdminController::class, 'destroy']);
});
