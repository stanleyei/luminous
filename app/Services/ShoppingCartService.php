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

    /**
     * 放棄購買商品
     * @param object $params 購買參數
     * @return array 回傳訊息
     */
    public function giveUp($params)
    {
        $product = Product::with('userClientProducts')
            ->select('id', 'successful_bidder_id')
            ->where('successful_bidder_id', $params->userClientId)
            ->find($params->id);

        if ($product) {
            $product->update([
                'successful_bidder_id' => 0,
            ]);

            $userClientProduct = $product->userClientProducts;
            $heightestItem = $userClientProduct->where('bid_price', $product->scopeHeightestPrice())->first();

            if ($heightestItem) {
                $heightestItem->delete();
            }
        }

        return ['message' => rtFormat($params->id)];
    }
}
