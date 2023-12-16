<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Product;

class IndexService
{
    public function __construct()
    {
    }

    /**
     * 獲取首頁資料
     * @return array
     */
    public function getIndexData()
    {
        $data = [
            'banners' => $this->getBannerData(),
            'products' => $this->getProductData(),
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 獲取Banner資料
     * @return array
     */
    protected function getBannerData()
    {
        $banners = Banner::select('id', 'photo_path', 'photo_alt')->whereNot('photo_path', '')->get();

        return $banners;
    }

    /**
     * 獲取4筆商品資料
     */
    public function getProductData()
    {
        $productData = Product::with('productPhotos')
            ->select('id', 'type', 'name', 'status', 'start_time', 'end_time', 'price', 'cover_photo_index', 'created_at')
            ->active()
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $data = $productData->map(function ($item) {
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
        });

        return $data;
    }
}
