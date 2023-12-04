<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductController;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Backend/Dashboard');
    })->name('dashboard');

    // 後台修改密碼頁
    Route::get('change-password', function () {
        return Inertia::render('Backend/ChangePassword');
    })->name('password.edit');

    // 後台商品管理
    Route::prefix('product')->group(function () {
        // 後台商品管理列表頁
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        // 後台商品管理新增編輯頁
        Route::get('edit', [ProductController::class, 'edit'])->name('product.edit');
        // 新增商品
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        // 更新商品
        Route::put('update', [ProductController::class, 'update'])->name('product.update');
        // 更新商品狀態
        Route::put('update-status', [ProductController::class, 'updateStatus'])->name('product.status');
        // 刪除商品
        Route::delete('destroy', [ProductController::class, 'destroy'])->name('product.destroy');
        // 上傳商品照片
        Route::post('upload-photo', [ProductController::class, 'uploadPhoto'])->name('product.upload');
    });
});
