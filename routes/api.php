<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    UserController,
    CategoryController,
    ProductController,
};

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('/users', UserController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/product', ProductController::class);
