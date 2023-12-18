<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ProductListController;
use App\Http\Controllers\Frontend\ClientCenterController;

Route::get('/', [IndexController::class, 'index'])->name('home');

// 前台商品列表頁
Route::prefix('product')->group(function () {
    Route::get('/', [ProductListController::class, 'index'])->name('product.index');
    Route::get('/detail', [ProductListController::class, 'detail'])->name('product.detail');
});

// 前台會員中心
Route::prefix('client')->middleware(['auth', 'role.weight:2'])->group(function () {
    Route::get('/', [ClientCenterController::class, 'index'])->name('client.index');
});
