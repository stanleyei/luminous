<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserClientProduct;
use App\Presenters\ProductPresenter;

class ProductListService
{
    public function __construct(protected ProductPresenter $productPresenter)
    {
    }

    /**
     * 獲取商品列表資料
     * @param object $params
     * @return array
     */
    public function getProductListData($params)
    {
        $productData = Product::with('productPhotos')
            ->select('id', 'type', 'name', 'status', 'start_time', 'end_time', 'price', 'cover_photo_index', 'created_at')
            ->active()
            ->where('name', 'like', "%{$params->keywords}%")
            ->when($params->type, fn ($query) => $query->where('type', $params->type))
            ->orderBy('created_at', 'desc');

        $data = [
            'currentType' => $params->type,
            'productTypeOption' => $this->productPresenter->getTypeOption(),
            'productData' => $productData->paginate(10)->through(function ($item) {
                // 封面照片
                $coverPhoto = $item->coverPhoto();

                return [
                    // 商品 id
                    'id' => $item->id,
                    // 商品名稱
                    'name' => $item->name,
                    // 競標開始時間
                    'start_time' => date('Y-m-d H:i', strtotime($item->start_time)),
                    // 競標結束時間
                    'end_time' => date('Y/m/d H:i', strtotime($item->end_time)),
                    // 商品價格
                    'price' => $item->price,
                    // 商品封面照片路徑
                    'cover_photo_path' => $coverPhoto->photo_path ?? '',
                ];
            }),
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 獲取商品詳細資料
     * @return array|object
     */
    public function getProductDetailData($params)
    {
        $product = Product::with('productPhotos')
            ->select('id', 'type', 'name', 'status', 'start_time', 'end_time', 'price', 'successful_bidder_id', 'is_paid', 'cover_photo_index', 'description', 'created_at')
            ->where('status', 1)
            ->where('start_time', '<=', now())
            ->find($params->id);

        if (!$product) {
            return null;
        }

        // 檢查目前會員是否競標過該產品
        $is_pay = 0;
        if (auth()->check() && auth()->user()?->userClient) {
            $userClientId = auth()->user()->userClient->id;
            $is_pay = ($product->successful_bidder_id === $userClientId && $product->is_paid) ? 1 : 0;
        }

        $data = [
            'productData' => [
                // 商品 id
                'id' => $product->id,
                // 商品名稱
                'name' => $product->name,
                // 競標結束時間
                'end_time' => date('Y-m-d\TH:i:s', strtotime($product->end_time)),
                // 目前最高競標價格
                'price' => $product->scopeHeightestPrice(),
                // 目前會員是否得標該產品且付款
                'is_pay' => $is_pay,
                // 商品封面照片 index
                'cover_photo_index' => $product->cover_photo_index,
                // 商品描述
                'description' => $product->description,
                // 商品照片
                'photos' => $product->productPhotos->map(function ($item) {
                    return [
                        'photo_path' => $item->photo_path,
                        'photo_alt' => $item->photo_alt,
                    ];
                }),
            ],
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 在會員產品清單中進行競標
     * @param object $params 競標參數，包含會員ID、產品ID和競標價格
     * @return array 包含訊息的陣列，訊息為競標成功後的會員產品ID
     */
    public function bidProduct($params)
    {
        $userClientId = $params->user_client_id;
        $productId = $params->product_id;
        $bidPrice = $params->bid_price;

        $whereQuerys = [
            ['user_client_id', $userClientId],
            ['product_id', $productId],
        ];

        // 檢查價格是否高於目前最高競標價格
        $product = Product::select('id', 'price', 'status', 'end_time')->find($productId);
        if ($bidPrice <= $product->scopeHeightestPrice()) {
            return ['message' => rtFormat($bidPrice, 0, '競標價格必須高於目前最高競標價格')];
        }

        // 檢查商品是否已經下架
        if ($product->status !== 1) {
            return ['message' => rtFormat($productId, 0, '該商品已經下架')];
        }

        // 檢查競標時間是否已經結束
        if (strtotime($product->end_time) < time()) {
            return ['message' => rtFormat($productId, 0, '該商品競標時間已經結束')];
        }

        // 檢查會員是否已經競標過該產品，若有則更新價格，若無則新增
        $userClientProduct = UserClientProduct::where($whereQuerys)->first();
        if ($userClientProduct) {
            $userClientProduct->update([
                'bid_price' => $bidPrice,
            ]);
        } else {
            UserClientProduct::create([
                'user_client_id' => $userClientId,
                'product_id' => $productId,
                'bid_price' => $bidPrice,
            ]);
        }

        return ['message' => rtFormat($productId)];
    }

    /**
     * 找出已結束競標且有會員競標的商品，並更新競標結果。
     * @return array
     */
    public function winningBid()
    {
        $whereQuerys = [
            ['status', 1],
            ['end_time', '<=', now()],
            ['successful_bidder_id', 0],
        ];

        // 找出所有已經結束競標，且有會員競標的商品
        $productData = Product::with('userClientProducts')
            ->select('id', 'status', 'start_time', 'end_time', 'successful_bidder_id')
            ->where($whereQuerys)
            ->get();

        // 找出每個商品的最高競標價格，並更新商品的 successful_bidder_id欄位
        foreach ($productData as $product) {
            $userClientProduct = $product->userClientProducts;
            $heightestItem = $userClientProduct->where('bid_price', $product->scopeHeightestPrice())->first();

            if ($heightestItem) {
                $product->update([
                    'successful_bidder_id' => $heightestItem->user_client_id,
                ]);
            }
        }

        return ['message' => rtFormat('')];
    }
}
