<?php

namespace App\Services;

use App\Models\Product;
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
            ->select('id', 'type', 'name', 'status', 'start_time', 'end_time', 'price', 'cover_photo_index', 'description', 'created_at')
            ->active()
            ->find($params->id);

        if (!$product) {
            return null;
        }

        $data = [
            'productData' => [
                // 商品 id
                'id' => $product->id,
                // 商品名稱
                'name' => $product->name,
                // 商品開始時間
                'start_time' => date('Y-m-d H:i:s', strtotime($product->start_time)),
                // 商品價格
                'price' => $product->price,
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
}
