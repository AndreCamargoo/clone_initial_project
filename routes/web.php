<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    // Category
    Route::match(['GET', 'POST'], '/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::resource('/categories', CategoryController::class);

    // Product
    Route::match(['GET', 'POST'], '/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('/products', ProductController::class);
});

