<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

// ==================== TRANG CHỦ & KHÁC ====================
Route::get('/', function () {
    $allProducts = include resource_path('dummy/products.php');
    
    // Chia sản phẩm theo từng tab
    $newProducts = collect($allProducts)->take(8); // 8 sản phẩm mới
    $featuredProducts = collect($allProducts)->skip(8)->take(8); // 8 sản phẩm nổi bật
    $saleProducts = collect($allProducts)->where('discount', '>', 0)->take(8); // 8 sản phẩm giảm giá
    $bestSellers = collect($allProducts)->take(16); // 16 sản phẩm bán chạy (2 tabs carousel)
    
    return view('pages.home', compact('newProducts', 'featuredProducts', 'saleProducts', 'bestSellers'));
})->name('home');

Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
Route::get('/tin-tuc', fn() => view('pages.blog.index'))->name('blog.index');
Route::get('/khuyen-mai', fn() => view('pages.super-deal'))->name('super-deal');

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