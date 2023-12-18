<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserClient;

class UserClientService
{
    public function __construct()
    {
    }

    /**
     * 根據提供的參數檢索會員列表
     * @param object $params 用於過濾和排序會員的參數
     * @return array 包含格式化會員數據的響應
     */
    public function getUserClientList($params)
    {
        $userClientData = UserClient::join('users', 'users.id', '=', 'user_clients.user_id')
            ->select('users.id', 'users.name', 'users.email', 'users.status', 'users.created_at', 'user_clients.phone')
            ->where('name', 'like', "%{$params->keywords}%");

        // 依照指定欄位正倒序
        $rules = [
            'id' => $params->sortId,
            'email' => $params->sortEmail,
            'phone' => $params->sortPhone,
        ];
        columnSort($userClientData, $rules);

        $userClientData->orderBy('created_at', 'desc');

        $data = [
            'userClientData' => $userClientData->paginate(10)->through(function ($item) {
                return [
                    // 會員編號
                    'id' => $item->id,
                    // 會員姓名
                    'email' => $item->email,
                    // 會員電話
                    'phone' => $item->phone,
                    // 會員狀態
                    'status' => $item->status,
                ];
            }),
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 預覽會員資料
     * @param int $id 會員ID
     * @return array 包含會員資料的回應
     */
    public function previewUserClient($id)
    {
        $userClient = UserClient::with('user', 'products')
            ->select('id', 'user_id', 'phone')
            ->whereHas('user', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();

        $user = $userClient->user;

        $data = [
            'userClientData' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $userClient->phone,
                'products' => $userClient->products->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'pivot_status' => $item->pivot->status,
                        'bid_price' => $item->pivot->bid_price,
                    ];
                }),
            ],
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 更新使用者狀態
     * @param int $id 使用者ID
     * @return array
     */
    public function toggleUserStatus($id)
    {
        $user = User::select('id', 'status')->find($id);

        $user->update([
            'status' => $user->status ? 0 : 1,
        ]);

        return ['message' => rtFormat($id)];
    }
}
