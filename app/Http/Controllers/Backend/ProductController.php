<?php

namespace App\Http\Controllers\Backend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $validateRule;

    // 初始化注入
    public function __construct(protected ProductService $productService)
    {
        $this->validateRule = [
            'type' => 'required|integer|between:1,3',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|integer|min:0|max:9999999',
            'description' => 'string|max:65535',
            'cover_photo_index' => 'required|integer|min:0',
            'photos' => 'array',
        ];
    }

    // 前往後台商品管理列表頁
    public function index(Request $request)
    {
        $params = (object) [
            'type' => (int) $request->type ?? 1,
            'keywords' => $request->q ?? '',
            'sortStartTime' => $request->sortStartTime,
            'sortName' => $request->sortName,
            'sortPrice' => $request->sortPrice,
        ];

        $rtData = $this->productService->getProductList($params);

        return Inertia::render('Backend/ProductFolder/ProductManage', $rtData);
    }

    // 前往後台商品管理新增編輯頁
    public function edit(Request $request)
    {
        $id = (int) $request->id ?? 0;
        $rtData = $this->productService->getProductData($id);

        return Inertia::render('Backend/ProductFolder/ProductAddEdit', $rtData);
    }

    // 新增商品
    public function store(Request $request)
    {
        // 驗證請求
        $request->validate($this->validateRule);

        // Modal 參數
        $modalParams = [
            'type' => $request->type,
            'name' => $request->name,
            'start_time' => date('Y-m-d H:i:s', strtotime($request->start_time)),
            'end_time' => date('Y-m-d H:i:s', strtotime($request->end_time)),
            'price' => $request->price,
            'description' => $request->description ?? '',
            'cover_photo_index' => $request->cover_photo_index,
        ];

        // 其他參數
        $otherParams = (object) [
            'photos' => $request->photos ?? [],
        ];

        $rtData = $this->productService->storeProduct($modalParams, $otherParams);

        return back()->with($rtData);
    }

    // 更新商品
    public function update(Request $request)
    {
        // 驗證請求
        $request->validate($this->validateRule + [
            'id' => 'required|integer|exists:products,id',
            'delete_photo_ids' => 'array',
        ]);

        // Modal 參數
        $modalParams = [
            'id' => $request->id,
            'type' => $request->type,
            'name' => $request->name,
            'start_time' => date('Y-m-d H:i:s', strtotime($request->start_time)),
            'end_time' => date('Y-m-d H:i:s', strtotime($request->end_time)),
            'price' => $request->price,
            'description' => $request->description ?? '',
            'cover_photo_index' => $request->cover_photo_index,
        ];

        // 其他參數
        $otherParams = (object) [
            'photos' => $request->photos ?? [],
            'delete_photo_ids' => $request->delete_photo_ids ?? [],
        ];

        $rtData = $this->productService->updateProduct($modalParams, $otherParams);

        return back()->with($rtData);
    }

    // 上下架商品
    public function updateStatus(Request $request)
    {
        // 驗證請求
        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'status' => 'required|integer|between:0,1',
        ]);

        $rtData = $this->productService->updateStatus($request->id, $request->status);

        return back()->with($rtData);
    }

    // 刪除商品
    public function destroy(Request $request)
    {
        // 驗證請求
        $request->validate([
            'id' => 'required|integer|exists:products,id',
        ]);

        $rtData = $this->productService->deleteProduct($request->id);

        return back()->with($rtData);
    }

    // 商品上傳圖片並回傳路徑
    public function uploadPhoto(Request $request)
    {
        // 驗證請求
        $request->validate([
            'img' => 'required|string',
        ]);

        $rtData = $this->productService->uploadImg($request->img);

        return back()->with($rtData);
    }
}
