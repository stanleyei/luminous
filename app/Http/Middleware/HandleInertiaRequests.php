<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
                'query' => count($request->query()) ? $request->query() : (object)[],
                'previous' => url()->previous(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
            'clientWinBidProductList' => $this->getWinBidProductListData($request),
        ];
    }

    /**
     * 取得中標商品清單資料
     * @param  \Illuminate\Http\Request  $request  HTTP 請求物件
     * @return array 中標商品清單資料
     */
    protected function getWinBidProductListData($request)
    {
        $user = $request->user();
        if (!$user || !$user?->userClient) return [];

        $clientProducts = $user->userClient->products ?? [];

        // 找出中標商品
        $clientProducts = $clientProducts->where('pivot.status', 2)->get();

        $data = $clientProducts->map(function ($item) {
            // 封面照片
            $coverPhoto = $item->coverPhoto();

            return [
                // 商品 id
                'id' => $item->id,
                // 商品名稱
                'name' => $item->name,
                // 商品價格
                'price' => $item->price,
                // 商品封面照片路徑
                'cover_photo_path' => $coverPhoto->photo_path ?? '',
            ];
        });

        return $data;
    }
}
