<?php

use Illuminate\Support\Facades\Route;

Route::get('/blog/create', fn() => view('pages.blog.create'))->name('blog.create');
