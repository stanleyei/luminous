<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\UserClientController;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Backend/Dashboard');
    })->name('dashboard');

    // 後台修改密碼頁
    Route::get('change-password', function () {
        return Inertia::render('Backend/ChangePassword');
    })->name('password.edit');

    // 後台商品管理
    Route::prefix('product')->controller(ProductController::class)->group(function () {
        // 後台商品管理列表頁
        Route::get('/', 'index')->name('product.list');
        // 後台商品管理新增編輯頁
        Route::get('edit', 'edit')->name('product.edit');
        // 新增商品
        Route::post('store', 'store')->name('product.store');
        // 更新商品
        Route::put('update', 'update')->name('product.update');
        // 更新商品狀態
        Route::put('update-status', 'updateStatus')->name('product.status');
        // 更新商品精選狀態
        Route::put('update-featured', 'updateFeatured')->name('product.featured');
        // 刪除商品
        Route::delete('destroy', 'destroy')->name('product.destroy');
        // 上傳商品照片
        Route::post('upload-photo', 'uploadPhoto')->name('product.upload');
    });

    // 後台會員管理
    Route::prefix('client')->controller(UserClientController::class)->group(function () {
        // 後台會員管理列表頁
        Route::get('/', 'index')->name('client.list');
        // 後台會員管理預覽頁
        Route::get('preview', 'preview')->name('client.preview');
        // 切換會員狀態
        Route::put('toggle-status', 'toggleStatus')->name('client.status');
    });

    // 後台 Banner 管理
    Route::prefix('banner')->controller(BannerController::class)->group(function () {
        // 後台 Banner 管理列表頁
        Route::get('/', 'index')->name('banner.list');
        // 後台 Banner 管理編輯頁
        Route::get('edit', 'edit')->name('banner.edit');
        // 更新 Banner
        Route::put('update', 'update')->name('banner.update');
    });
});
