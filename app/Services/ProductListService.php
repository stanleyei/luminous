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
     * @return array
     */
    public function getProductListData($params)
    {
        $whereQuery = [
            ['type', $params->type],
            ['name', 'like', "%{$params->keywords}%"],
        ];

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
                    // 商品開始時間
                    'start_time' => date('Y-m-d H:i', strtotime($item->start_time)),
                    // 商品價格
                    'price' => $item->price,
                    // 商品封面照片路徑
                    'cover_photo_path' => $coverPhoto->photo_path ?? '',
                ];
            }),
        ];

        return ['response' => rtFormat($data)];
    }
}
