<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductListService;

class ProductListController extends Controller
{
    // 初始化注入
    public function __construct(protected ProductListService $productListService)
    {
    }

    // 前往商品列表頁
    public function index(Request $request)
    {
        $params = (object) [
            'type' => (int) ($request->type ?? 0),
            'keywords' => $request->q ?? '',
        ];

        $rtData = $this->productListService->getProductListData($params);

        return Inertia::render('Frontend/ProductList', $rtData);
    }

    // 前往商品詳細頁
    public function detail(Request $request)
    {
        return Inertia::render('Frontend/ProductDetail');
    }
}
