<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Auth\AuthController;
use \App\Http\Controllers\Public;

// ==================== TRANG CHỦ & KHÁC ====================
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
Route::get('/tin-tuc', fn() => view('pages.blog.index'))->name('blog.index');
Route::get('/khuyen-mai', fn() => view('pages.super-deal'))->name('super-deal');

// ==================== SẢN PHẨM ====================
Route::get('/san-pham', [\App\Http\Controllers\Public\ProductController::class, 'index'])->name('products.index');

Route::get('/san-pham/{slug}', [\App\Http\Controllers\Public\ProductController::class, 'showBySlugView'])->name('products.show');

Route::get('/products/{slug}', [Public\ProductController::class, 'showBySlugView']);

// ==================== ĐĂNG NHẬP & ĐĂNG KÝ ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ==================== GIỎ HÀNG ====================
Route::get('/cart', [\App\Http\Controllers\Me\CartController::class, 'index'])->name('cart.page');

// ==================== THANH TOÁN ====================
Route::get('/checkout', [\App\Http\Controllers\Me\OrderController::class, 'checkout'])->name('order.checkout');
