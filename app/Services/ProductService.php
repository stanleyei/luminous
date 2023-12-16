<?php

namespace App\Services;

use App\Models\Product;
use App\Presenters\ProductPresenter;

class ProductService
{
    public function __construct(
        protected FilesService $filesService,
        protected ProductPresenter $productPresenter
    ) {
    }

    /**
     * 獲取商品資料列表
     * @param object $params 參數物件
     * @return array
     */
    public function getProductList($params)
    {
        $productData = Product::with('productPhotos')
            ->select('id', 'type', 'name', 'status', 'start_time', 'price', 'cover_photo_index', 'created_at')
            ->where('name', 'like', "%{$params->keywords}%")
            ->when($params->type, fn ($query) => $query->where('type', $params->type));

        // 依照指定欄位正倒序
        $rules = [
            // 傳 1 或 2 日期排序
            'start_time' => $params?->sortStartTime ?? 0,
            // 傳 1 或 2 名稱排序
            'name' => $params?->sortName ?? 0,
            // 傳 1 或 2 發布單位排序
            'price' => $params?->sortPrice ?? 0,
        ];
        columnSort($productData, $rules);

        $productData->orderBy('created_at', 'desc');

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
                    // 商品狀態
                    'status' => $item->status,
                    // 商品開始時間
                    'start_time' => date('Y-m-d H:i', strtotime($item->start_time)),
                    // 商品價格
                    'price' => $item->price,
                    // 商品封面照片路徑
                    'cover_photo_path' => $coverPhoto->photo_path ?? '',
                    // 商品封面照片說明
                    'cover_photo_alt' => $coverPhoto->photo_alt ?? '',
                ];
            }),
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 獲取商品資料
     * @param int $id 商品 id
     * @return array
     */
    public function getProductData($id)
    {
        // 獲取商品資料
        $productData = Product::with('productPhotos')->find($id);

        $data = [
            'productData' => [
                // 商品 id
                'id' => $id,
                // 商品類別
                'type' => $productData?->type ?? '',
                // 商品名稱
                'name' => $productData?->name ?? '',
                // 商品開始時間
                'start_time' => $productData->start_time ?? '',
                // 商品結束時間
                'end_time' => $productData->end_time ?? '',
                // 商品價格
                'price' => $productData?->price ?? '',
                // 商品描述
                'description' => $productData?->description ?? '',
                // 商品照片
                'photos' => $productData?->productPhotos ?? [],
            ],
            'productTypeOption' => $this->productPresenter->getTypeOption(),
        ];

        return ['response' => rtFormat($data)];
    }

    /**
     * 新增商品
     * @param object $modalParams Model參數物件
     * @param object $otherParams 其他參數物件
     * @return array
     */
    public function storeProduct($modalParams, $otherParams)
    {
        $productData = Product::create($modalParams);

        // 新增編輯相簿照片及相簿照片描述
        if (count($otherParams->photos)) {
            $this->handlePhoto($productData, $otherParams->photos);
        }

        return ['message' => rtFormat($productData->id)];
    }

    /**
     * 更新商品
     * @param object $modalParams Model參數物件
     * @param object $otherParams 其他參數物件
     * @return array
     */
    public function updateProduct($modalParams, $otherParams)
    {
        $productData = Product::with('productPhotos')->find($otherParams->id);
        $productData->update($modalParams);

        // 新增編輯相簿照片及相簿照片描述
        if (count($otherParams->photos)) {
            $this->handlePhoto($productData, $otherParams->photos);
        }

        // 刪除相簿照片
        if (count($otherParams->deletePhotoIds)) {
            $this->deletePhoto($productData, $otherParams->deletePhotoIds);
        }

        return ['message' => rtFormat($otherParams->id)];
    }

    /**
     * 更新商品狀態
     * @param int $id 商品 id
     * @param int $status 商品狀態
     * @return array
     */
    public function updateStatus($id)
    {
        $productData = Product::select('id', 'status')->find($id);
        $status = $productData->status ? 0 : 1;
        $productData->update(['status' => $status]);

        return ['message' => rtFormat($id)];
    }

    /**
     * 刪除商品
     * @param int $id 商品 id
     * @return array
     */
    public function deleteProduct($id)
    {
        $productData = Product::with('productPhotos')->find($id);

        // 刪除商品照片
        $this->deletePhoto($productData, $productData->productPhotos->pluck('id')->toArray());

        $productData->delete();

        return ['message' => rtFormat($id)];
    }

    /**
     * 上傳圖片並回傳路徑
     * @param string $img base64圖片
     * @return string
     */
    public function uploadImg($img)
    {
        $imgPath = $this->filesService->base64Upload($img, 'product');
        $res = $imgPath ? rtFormat($imgPath) : rtFormat('', 0, '上傳失敗');

        return ['message' => $res];
    }

    /**
     * 處理產品照片
     * @param Product $productData 產品資料
     * @param array $photos 圖片陣列
     */
    protected function handlePhoto($productData, $photos)
    {
        foreach ($photos as $photo) {
            $objPhoto = (object) $photo;

            // 判斷是否有存在圖片路徑
            $productPhoto = $productData
                ->productPhotos()
                ->where('photo_path', $objPhoto->photo_path)
                ->first();

            // 判斷是新增還是更新
            if ($productPhoto) {
                $productPhoto->update([
                    'photo_alt' => $objPhoto->photo_alt,
                ]);
            } else {
                $productData->productPhotos()->create([
                    'photo_path' => $objPhoto->photo_path,
                    'photo_alt' => $objPhoto->photo_alt,
                ]);
            }
        }
    }

    /**
     * 刪除商品圖片
     * @param Product $productData 商品資料
     * @param array $deleteIds 要刪除的圖片id陣列
     */
    protected function deletePhoto($productData, $deleteIds)
    {
        $productDataRelation = $productData->productPhotos ?? [];
        foreach ($deleteIds as $id) {
            $productDataRelationFile = $productDataRelation->find($id);
            if ($productDataRelationFile) {
                $this->filesService->deleteUpload($productDataRelationFile->photo_path);
                $productDataRelationFile->delete();
            }
        }
    }
}
