<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;

class ProductListController extends Controller
{
    // 初始化注入
    public function __construct(protected ProductService $productService)
    {
    }

    // 前往商品列表頁
    public function index(Request $request)
    {
        $params = (object) [
            'type' => (int) ($request->type ?? 0),
            'keywords' => $request->q ?? '',
        ];

        $rtData = $this->productService->getProductList($params);

        return Inertia::render('Frontend/ProductList', $rtData);
    }

    // 前往商品詳細頁
    public function detail(Request $request)
    {
        return Inertia::render('Frontend/ProductDetail');
    }
}
