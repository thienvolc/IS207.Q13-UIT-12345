<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

// ==================== TRANG CHỦ & KHÁC ====================
Route::get('/', function () {
    // Lấy dữ liệu từ Aiven Cloud Database
    $products = DB::table('products')
        ->where('status', 1)
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->title,
                'slug' => $product->slug,
                'thumbnail' => $product->thumb,
                'price' => $product->price,
                'price_sale' => $product->discount ? ($product->price - ($product->price * $product->discount / 100)) : $product->price,
                'discount' => $product->discount ?? 0,
                'category' => $product->type,
                'quantity' => $product->quantity,
                'description' => $product->desc,
            ];
        });

    // Chia sản phẩm theo từng tab
    $newProducts = $products->take(8); // 8 sản phẩm mới
    $featuredProducts = $products->skip(8)->take(8); // 8 sản phẩm nổi bật
    $saleProducts = $products->where('discount', '>', 0)->take(8); // 8 sản phẩm giảm giá
    $bestSellers = $products->take(16); // 16 sản phẩm bán chạy (2 tabs carousel)

    return view('pages.home', compact('newProducts', 'featuredProducts', 'saleProducts', 'bestSellers'));
})->name('home');

Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
Route::get('/tin-tuc', fn() => view('pages.blog.index'))->name('blog.index');
Route::get('/khuyen-mai', fn() => view('pages.super-deal'))->name('super-deal');

// ==================== SẢN PHẨM ====================

// Danh sách sản phẩm
Route::get('/san-pham', function () {
    $perPage = 12;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    // Lấy dữ liệu từ database với phân trang
    $query = DB::table('products')
        ->where('status', 1)
        ->orderBy('created_at', 'desc');

    $total = $query->count();
    $items = $query
        ->skip(($currentPage - 1) * $perPage)
        ->take($perPage)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->title,
                'slug' => $product->slug,
                'thumbnail' => $product->thumb,
                'price' => $product->price,
                'price_sale' => $product->discount ? ($product->price - ($product->price * $product->discount / 100)) : $product->price,
                'discount' => $product->discount ?? 0,
                'category' => $product->type,
                'quantity' => $product->quantity,
            ];
        })->toArray();

    $products = new LengthAwarePaginator(
        $items,
        $total,
        $perPage,
        $currentPage,
        ['path' => url('/san-pham')]
    );

    return view('pages.products.index', compact('products'));
})->name('products.index');

// Chi tiết sản phẩm
Route::get('/san-pham/{slug}', function ($slug) {
    // Lấy sản phẩm theo slug
    $productData = DB::table('products')
        ->where('slug', $slug)
        ->where('status', 1)
        ->first();

    if (!$productData) {
        abort(404);
    }

    $product = [
        'id' => $productData->product_id,
        'name' => $productData->title,
        'slug' => $productData->slug,
        'thumbnail' => $productData->thumb,
        'price' => $productData->price,
        'price_sale' => $productData->discount ? ($productData->price - ($productData->price * $productData->discount / 100)) : $productData->price,
        'discount' => $productData->discount ?? 0,
        'category' => $productData->type,
        'quantity' => $productData->quantity,
        'description' => $productData->desc,
        'summary' => $productData->summary,
    ];

    // Sản phẩm liên quan (cùng loại)
    $related = DB::table('products')
        ->where('type', $productData->type)
        ->where('product_id', '!=', $productData->product_id)
        ->where('status', 1)
        ->inRandomOrder()
        ->limit(4)
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->product_id,
                'name' => $p->title,
                'slug' => $p->slug,
                'thumbnail' => $p->thumb,
                'price' => $p->price,
                'price_sale' => $p->discount ? ($p->price - ($p->price * $p->discount / 100)) : $p->price,
                'discount' => $p->discount ?? 0,
                'category' => $p->type,
            ];
        });

    return view('pages.products.detail', compact('product', 'related'));
})->name('products.show');
