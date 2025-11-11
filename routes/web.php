<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

// ==================== TRANG CHỦ & KHÁC ====================
Route::get('/', function () {
    // ============================================
    // ✅ DATA THẬT - Lấy từ Aiven Cloud Database
    // ============================================

    // Hero Slider - 3 sản phẩm nổi bật
    $heroProducts = DB::table('products')
        ->where('status', 1)
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->title,
                'slug' => $product->slug,
                'thumbnail' => $product->thumb,
                'price' => $product->price,
                'price_sale' => $product->price,
            ];
        });

    // Category Banners - 4 categories từ DB
    $categoryBanners = DB::table('categories')
        ->limit(4)
        ->get(['category_id', 'title', 'slug']);

    // Products cho tabs và carousel
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

    // ============================================
    // 🔒 HARDCODE TẠM - Tab Giảm Giá (Chờ discount data)
    // ============================================
    // Lý do: Database không có discount (all NULL)
    // TODO: Khi có discount data, thay bằng:
    //   $saleProducts = $products->where('discount', '>', 0)->take(8);
    // Date: 2025-11-11
    // ============================================
    $saleProducts = $products->skip(16)->take(8)->map(function ($p) {
        // FAKE discount for demo
        $p['discount'] = rand(10, 30);
        $p['price_sale'] = $p['price'] * (1 - $p['discount'] / 100);
        return $p;
    });

    $bestSellers = $products->take(16); // 16 sản phẩm bán chạy (2 tabs carousel)

    return view('pages.home', compact('heroProducts', 'categoryBanners', 'newProducts', 'featuredProducts', 'saleProducts', 'bestSellers'));
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

    // ============================================
    // ✅ SEARCH & FILTER - Từ query parameters
    // ============================================
    $query = DB::table('products')->where('status', 1);

    // Search by name
    if ($search = request('q')) {
        $query->where('title', 'like', "%{$search}%");
    }

    // Filter by category
    if ($category = request('category')) {
        $query->where('slug', 'like', "{$category}%");
    }

    // Filter by price range
    if ($priceMin = request('price_min')) {
        $query->where('price', '>=', $priceMin);
    }
    if ($priceMax = request('price_max')) {
        $query->where('price', '<=', $priceMax);
    }

    // Sort
    $sort = request('sort', 'newest');
    match ($sort) {
        'price_asc' => $query->orderBy('price', 'asc'),
        'price_desc' => $query->orderBy('price', 'desc'),
        'name' => $query->orderBy('title', 'asc'),
        default => $query->orderBy('created_at', 'desc'),
    };

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

    // Preserve query string in pagination
    $products->appends(request()->query());

    return view('pages.products.index', compact('products'));
})->name('products.index');

// Chi tiết sản phẩm
Route::get('/san-pham/{slug}', function ($slug) {
    // ============================================
    // ✅ DATA THẬT - Product từ Aiven Cloud
    // ============================================

    // Lấy sản phẩm theo slug
    $productData = DB::table('products')
        ->where('slug', $slug)
        ->where('status', 1)
        ->first();

    if (!$productData) {
        abort(404);
    }

    // Lấy product metas
    $metas = DB::table('product_metas')
        ->where('product_id', $productData->product_id)
        ->get()
        ->pluck('content', 'key')
        ->toArray();

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

        // ============================================
        // 🔒 HARDCODE TẠM - Fake data chờ field thật
        // ============================================
        // TODO: Tính từ bảng reviews khi có
        'rating' => 4.5,
        'reviews_count' => rand(10, 100),
        'sold' => rand(50, 500),

        // TODO: Parse từ description hoặc tạo field mới
        'highlights' => [
            'Chất lượng cao, hiệu suất tốt',
            'Thiết kế đẹp, hiện đại',
            'Bảo hành chính hãng',
        ],

        // ============================================
        // ✅ SPECS THẬT - Map từ product_metas
        // ============================================
        // Map keys phù hợp với loại sản phẩm (Loa/Tai nghe)
        'specs' => [
            'Thương hiệu' => $metas['brand'] ?? 'N/A',
            'Công suất' => $metas['rms_watt'] ?? null,
            'Thời lượng pin' => $metas['battery_life_h'] ?? null,
            'Chống nước' => $metas['waterproof_ip'] ?? null,
            'Bluetooth' => $metas['bluetooth_version'] ?? null,
            'Bảo hành' => $metas['warranty_months'] ?? null,
        ],
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
