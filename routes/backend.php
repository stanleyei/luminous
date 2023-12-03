<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Backend/Dashboard');
    })->name('dashboard');

    // 後台修改密碼頁
    Route::get('change-password', function () {
        return Inertia::render('Backend/ChangePassword');
    })->name('password.edit');
});
