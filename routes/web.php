<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

// ==================== TRANG CHỦ & KHÁC ====================
Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
Route::get('/tin-tuc', fn() => view('pages.blog.index'))->name('blog.index');
Route::get('/khuyen-mai', fn() => view('pages.super-deal'))->name('super-deal');
Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
// ==================== SẢN PHẨM ====================

// Danh sách sản phẩm
Route::get('/san-pham', function () {
    $allProducts = include resource_path('dummy/products.php');
    $perPage = 12;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = array_slice($allProducts, ($currentPage - 1) * $perPage, $perPage);

    $products = new LengthAwarePaginator(
        $currentItems,
        count($allProducts),
        $perPage,
        $currentPage,
        ['path' => url('/san-pham')]
    );

    return view('pages.products.index', compact('products'));
})->name('products.index');

// Chi tiết sản phẩm
Route::get('/san-pham/{slug}', function ($slug) {
    $allProducts = include resource_path('dummy/products.php');
    $product = collect($allProducts)->firstWhere('slug', $slug);

    if (!$product) {
        abort(404);
    }

    // Sản phẩm liên quan (ngẫu nhiên 4 cái)
    $related = collect($allProducts)
        ->where('id', '!=', $product['id'])
        ->random(4);

    return view('pages.products.detail', compact('product', 'related'));
})->name('products.show');