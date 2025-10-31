<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'));
Route::get('/super-deal', fn() => view('super-deal'));
?>