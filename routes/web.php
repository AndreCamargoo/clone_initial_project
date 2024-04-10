<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Category
Route::any('admin/categories/search', [CategoryController::class, 'search'])->name('categories.search');
Route::resource('admin/categories', CategoryController::class);

// Product
Route::any('admin/products/search', [ProductController::class, 'search'])->name('products.search');
Route::resource('admin/products', ProductController::class);