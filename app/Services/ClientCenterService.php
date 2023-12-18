<?php

namespace App\Services;

use App\Models\User;

class ClientCenterService
{
    public function __construct()
    {
    }

    // 取得會員資料
    public function getUserClientData($user)
    {
        $userClient = $user->userClient;
        $clientProducts = $userClient->products;
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $userClient->phone,
        ];

        $data = [
            'user' => $userData,
            'clientProducts' => $clientProducts,
        ];

        return ['response' => rtFormat($data)];
    }
}
