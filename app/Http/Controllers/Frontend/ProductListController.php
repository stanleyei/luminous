<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductListController extends Controller
{
    // 前往商品列表頁
    public function index(Request $request)
    {
        $keywords = $request->q ?? '';

        return Inertia::render('Frontend/ProductList');
    }

    // 前往商品詳細頁
    public function detail(Request $request)
    {
        return Inertia::render('Frontend/ProductDetail');
    }
}
