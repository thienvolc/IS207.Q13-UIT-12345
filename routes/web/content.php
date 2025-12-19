<?php

use App\Http\Controllers\Web\Content\UserBlogController;
use Illuminate\Support\Facades\Route;

// Blog routes - requires authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/blog/create', fn() => view('pages.blog.create'))->name('blog.create');
    Route::post('/blog/store', [UserBlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}/edit', [UserBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [UserBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{id}', [UserBlogController::class, 'destroy'])->name('blog.destroy');
    Route::get('/account/my-posts', [UserBlogController::class, 'myPosts'])->name('account.my-posts');
});
