<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserClient;
use App\Presenters\ProductPresenter;

class UserClientService
{
    public function __construct(protected ProductPresenter $productPresenter)
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
            ->select('id', 'user_id')
            ->whereHas('user', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();

        // 正在競標的商品
        $biddingProducts = $userClient->products->where('successful_bidder_id', '!=', $userClient->id);

        $user = $userClient->user;

        $data = [
            'userClientData' => [
                'id' => $user->id,
                'email' => $user->email,
                'biddingProducts' => $biddingProducts->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'type' => $this->productPresenter->getProductType($item->type)?->name ?? '',
                        'name' => $item->name,
                        'cover_photo_path' => $item->coverPhoto()->photo_path ?? '',
                        'bid_price' => $item->pivot->bid_price,
                    ];
                }),
                'successfulBidProducts' => $this->getSuccessfulBidProducts($user),
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

    /**
     * 取得中標商品清單資料
     * @param  \App\Models\User $user 使用者資料
     * @return array 中標商品清單資料
     */
    public function getSuccessfulBidProducts($user)
    {
        $userClient = $user?->userClient ?? null;
        if (!$user || !$userClient) return [];

        $clientProducts = $userClient->products ?? [];

        // 找出中標商品
        $clientProducts = $clientProducts
            ->where('is_paid', 0)
            ->where('successful_bidder_id', $userClient->id);

        $data = $clientProducts->map(function ($item) {
            // 封面照片
            $coverPhoto = $item->coverPhoto();

            return [
                // 商品 id
                'id' => $item->id,
                // 商品類型
                'type' => $this->productPresenter->getProductType($item->type)?->name ?? '',
                // 商品名稱
                'name' => $item->name,
                // 決標時間
                'end_time' => date('Y-m-d H:i', strtotime($item->end_time)),
                // 出標價格
                'bid_price' => $item->pivot->bid_price ?? 0,
                // 商品封面照片路徑
                'cover_photo_path' => $coverPhoto->photo_path ?? '',
            ];
        });

        return $data;
    }
}
