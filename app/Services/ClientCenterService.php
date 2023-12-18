<?php

namespace App\Services;

class ClientCenterService
{
    public function __construct()
    {
    }

    // 取得會員資料
    public function getUserClientData($user)
    {
        $userClient = $user->userClient;
        $clientProducts = $userClient->products->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'end_time' => date('Y-m-d H:i', strtotime($item->end_time)),
                'cover_photo_path' => $item->coverPhoto()->photo_path ?? '',
                'pivot_status' => $item->pivot->status,
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
            'clientProducts' => $clientProducts,
        ];

        return ['response' => rtFormat($data)];
    }
}
