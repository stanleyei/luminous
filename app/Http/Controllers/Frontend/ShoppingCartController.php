<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingCartController extends Controller
{
    // 前往購物車頁
    public function index(Request $request)
    {
        return Inertia::render('Frontend/ShoppingCart');
    }

    // 付款
    public function pay(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:products,id',
        ]);
    }
}
