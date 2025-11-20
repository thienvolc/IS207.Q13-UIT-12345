<?php

use App\Http\Controllers\Api\Admin\Identity\UserAdminController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

$auth = AuthMiddleware::class;

Route::prefix('admin/users')->middleware($auth . ':admin')->controller(UserAdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{user_id}', 'show');
    Route::delete('{user_id}', 'destroy');
    Route::patch('{user_id}/status', 'updateStatus');
    Route::patch('{user_id}/roles', 'updateRoles');
});
