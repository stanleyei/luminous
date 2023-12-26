<?php

namespace App\Services;

use App\Services\UserClientService;

class ClientCenterService
{
    public function __construct(protected UserClientService $userClientService)
    {
    }

    // 取得會員資料
    public function getUserClientData($user)
    {
        $userClient = $user->userClient;
        // 正在競標的商品
        $biddingProducts = $userClient->products->where('successful_bidder_id', '!=', $userClient->id);
        $biddingProductList = $biddingProducts->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'end_time' => date('Y-m-d H:i', strtotime($item->end_time)),
                'cover_photo_path' => $item->coverPhoto()->photo_path ?? '',
                'bid_price' => $item->pivot->bid_price,
                'highest_bid_price' => $item->scopeHeightestPrice(),
            ];
        });

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $userClient->phone,
        ];

        $data = [
            'userClientData' => $userData,
            'biddingProducts' => $biddingProductList,
            'successfulBidProducts' => $this->userClientService->getSuccessfulBidProducts($user),
        ];

        return ['response' => rtFormat($data)];
    }
}
