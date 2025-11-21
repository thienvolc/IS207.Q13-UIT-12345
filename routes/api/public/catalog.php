<?php

use App\Http\Controllers\Api\Public\Catalog\CategoryPublicController;
use App\Http\Controllers\Api\Public\Catalog\ProductPublicController;
use App\Http\Controllers\Api\Public\Catalog\TagPublicController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->controller(ProductPublicController::class)->group(function () {
    Route::get('/', 'search');
    Route::get('{product_id}/related', 'related');
    Route::get('{product_id}', 'show')->whereNumber('product_id');
    Route::get('{slug}', 'showBySlug')->where('slug', '^(?![0-9]+$)[a-zA-Z0-9\-]+$');
});

Route::controller(ProductPublicController::class)->group(function () {
    Route::get('categories/{slug}/products', 'searchByCategorySlug')->where('slug', '^(?![0-9]+$)[a-zA-Z0-9\-]+$');
    Route::get('tags/{tag_id}/products', 'searchByTagId')->whereNumber('tag_id');
});

Route::prefix('categories')->controller(CategoryPublicController::class)->group(function () {
    Route::get('/', 'search');
    Route::get('all', 'all');
    Route::get('{slug}', 'showBySlug')->where("slug", '^(?![0-9]+$)[a-zA-Z0-9\-]+$');
});

Route::prefix('tags')->controller(TagPublicController::class)->group(function () {
    Route::get('/', 'search');
});
