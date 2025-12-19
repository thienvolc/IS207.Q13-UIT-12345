<?php

use App\Http\Controllers\Api\Admin\Content\BlogAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/blogs')->name('api.admin.blogs.')->group(function () {
    Route::get('/', [BlogAdminController::class, 'index'])->name('index');
    Route::get('/{id}', [BlogAdminController::class, 'show'])->name('show');
    Route::post('/', [BlogAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [BlogAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [BlogAdminController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/publish', [BlogAdminController::class, 'publish'])->name('publish');
});
