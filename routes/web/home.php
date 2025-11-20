<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\BlogPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/lien-he', fn() => view('pages.contact'))->name('contact');
Route::get('/tin-tuc', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/khuyen-mai', fn() => view('pages.super-deal'))->name('super-deal');
