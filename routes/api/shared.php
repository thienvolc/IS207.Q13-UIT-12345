<?php

use App\Http\Controllers\Api\Shared\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('upload')->name('upload.')->controller(FileUploadController::class)->group(function () {
    Route::post('/image', 'uploadImage')
        ->name('image');

    Route::post('/images', 'uploadMultipleImages')
        ->name('images');

    Route::delete('/image/{publicId}', 'deleteImage')
        ->where('publicId', '.*')
        ->name('image.delete');

    Route::delete('/images', 'deleteMultipleImages')
        ->name('images.delete');
});
