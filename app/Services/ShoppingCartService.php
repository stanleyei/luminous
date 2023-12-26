<?php

namespace App\Services;

use App\Models\Product;

class ShoppingCartService
{
    public function __construct()
    {
    }

    /**
     * 處理付款功能
     * @param object $params 付款參數
     * @return array 付款結果訊息
     */
    public function payment($params)
    {
        $ids = $params->ids ?? [];

        $productData = Product::select('id', 'is_paid')
            ->whereIn('id', $ids)
            ->get();

        foreach ($productData as $product) {
            $product->update([
                'is_paid' => 1,
            ]);
        }

        return ['message' => rtFormat($ids)];
    }
}
