<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ProductListController;
use App\Http\Controllers\Frontend\ClientCenterController;
use App\Http\Controllers\Frontend\ShoppingCartController;

Route::get('/', [IndexController::class, 'index'])->name('home');

// 前台商品列表頁
Route::prefix('product')->controller(ProductListController::class)->group(function () {
    Route::get('/', 'index')->name('product.index');
    Route::get('/detail', 'detail')->name('product.detail');
    Route::post('/bid', 'bid')->middleware(['auth', 'role.weight:2'])->name('product.bid');
});

// 前台會員中心
Route::prefix('client')->middleware(['auth', 'role.weight:2'])->controller(ClientCenterController::class)->group(function () {
    Route::get('/', 'index')->name('client.index');
});

// 前台購物車
Route::prefix('shopping-cart')->middleware(['auth', 'role.weight:2'])->controller(ShoppingCartController::class)->group(function () {
    Route::get('/', 'index')->name('shoppingCart.index');
    Route::post('/pay', 'pay')->name('shoppingCart.pay');
    Route::delete('/give-up', 'giveUp')->name('shoppingCart.giveUp');
});
