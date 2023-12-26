<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ShoppingCartService;

class ShoppingCartController extends Controller
{
    public function __construct(protected ShoppingCartService $shoppingCartService)
    {
    }

    // 前往購物車頁
    public function index()
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

        $params = (object) $request->all();

        $rtData = $this->shoppingCartService->payment($params);

        return back()->with($rtData);
    }
}
