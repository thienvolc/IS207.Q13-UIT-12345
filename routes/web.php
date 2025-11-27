<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Auth\AuthController;
use \App\Http\Controllers\Public;
use Illuminate\Support\Facades\Auth;


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

// ==================== ADMIN ====================
// Logout route (for blade logout form)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// ---------- Admin routes ----------
Route::prefix('admin')
    ->name('admin.')
    // ->middleware(['auth','is_admin'])   // dùng khi BE đã có middleware is_admin
    ->middleware([]) // tạm bỏ middleware để FE dev (thay bằng ['auth','is_admin'] khi backend sẵn sàng)
    ->group(function () {

        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])
            ->name('dashboard');

        // Nếu bạn đã có ProductController, OrderController... ở App\Http\Controllers\Admin
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        Route::resource('customers', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show']);
        Route::resource('posts', \App\Http\Controllers\Admin\TagController::class);
    });

