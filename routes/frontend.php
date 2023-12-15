<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ProductListController;

Route::get('/', [IndexController::class, 'index'])->name('home');

// 前台商品列表頁
Route::prefix('product')->group(function () {
    Route::get('/', [ProductListController::class, 'index'])->name('product.index');
    Route::get('/detail', [ProductListController::class, 'detail'])->name('product.detail');
});
