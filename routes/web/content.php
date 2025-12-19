<?php

use App\Http\Controllers\Web\Content\UserBlogController;
use Illuminate\Support\Facades\Route;

// Blog routes - requires authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/blog/create', fn() => view('pages.blog.create'))->name('blog.create');
    Route::post('/blog/store', [UserBlogController::class, 'store'])->name('blog.store');
    Route::get('/account/my-posts', [UserBlogController::class, 'myPosts'])->name('account.my-posts');
});
