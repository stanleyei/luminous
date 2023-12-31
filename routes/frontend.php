<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ProductListController;
use App\Http\Controllers\Frontend\ClientCenterController;
use App\Http\Controllers\Frontend\ShoppingCartController;

Route::get('/', [IndexController::class, 'index'])->name('home');

// 前台商品列表頁
Route::prefix('product')->group(function () {
    Route::get('/', [ProductListController::class, 'index'])->name('product.index');
    Route::get('/detail', [ProductListController::class, 'detail'])->name('product.detail');
    Route::post('/bid', [ProductListController::class, 'bid'])->middleware(['auth', 'role.weight:2'])->name('product.bid');
});

// 前台會員中心
Route::prefix('client')->middleware(['auth', 'role.weight:2'])->group(function () {
    Route::get('/', [ClientCenterController::class, 'index'])->name('client.index');
});

// 前台購物車
Route::prefix('shopping-cart')->middleware(['auth', 'role.weight:2'])->group(function () {
    Route::get('/', [ShoppingCartController::class, 'index'])->name('shoppingCart.index');
    Route::post('/pay', [ShoppingCartController::class, 'pay'])->name('shoppingCart.pay');
    Route::delete('/give-up', [ShoppingCartController::class, 'giveUp'])->name('shoppingCart.giveUp');
});
