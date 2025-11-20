<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hero Slider - 3 sản phẩm nổi bật
        $heroProducts = DB::table('products')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($product) {
                return [
                    'product_id' => $product->product_id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'thumb' => $product->thumb,
                    'price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'quantity' => $product->quantity,
                    'desc' => $product->desc,
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
                    'product_id' => $product->product_id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'thumb' => $product->thumb,
                    'price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'quantity' => $product->quantity,
                    'desc' => $product->desc,
                ];
            });

        $newProducts = $products->take(8); // 8 sản phẩm mới
        $featuredProducts = $products->skip(8)->take(8); // 8 sản phẩm nổi bật

        // Tab Giảm Giá (chờ discount data)
        $saleProducts = $products->skip(16)->take(8)->map(function ($p) {
            $p['discount'] = rand(10, 30);
            $p['price_sale'] = $p['price'] * (1 - $p['discount'] / 100);
            return $p;
        });

        $bestSellers = $products->take(16); // 16 sản phẩm bán chạy

        return view('pages.home', compact('heroProducts', 'categoryBanners', 'newProducts', 'featuredProducts', 'saleProducts', 'bestSellers'));
    }
}
