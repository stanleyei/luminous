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
        $params = (object) [
            'id' => (int) ($request->id ?? 0),
        ];

        $rtData = $this->productListService->getProductDetailData($params);

        if (!$rtData) {
            return redirect(route('product.detail'));
        }

        return Inertia::render('Frontend/ProductDetail', $rtData);
    }

    // 競標商品
    public function bid(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'price' => 'required|integer|min:1|max:99999999',
        ]);

        $user = $request->user();
        $userClient = $user->userClient;

        $params = (object) [
            'user_client_id' => $userClient->id,
            'product_id' => (int) $request->id,
            'bid_price' => (int) $request->price,
        ];

        $rtData = $this->productListService->bidProduct($params);

        return back()->with($rtData);
    }
}
