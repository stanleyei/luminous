<?php

namespace App\Presenters;

class ProductPresenter
{
    /**
     * 商品類別選項陣列
     */
    protected $typeOption;

    public function __construct()
    {
        $this->typeOption = [
            (object) [
                'id' => 1,
                'name' => '項鍊',
            ],
            (object) [
                'id' => 2,
                'name' => '耳環',
            ],
            (object) [
                'id' => 3,
                'name' => '戒指',
            ],
        ];
    }

    /**
     * 回傳商品類別選項陣列
     * @return array 回傳商品類別選項陣列
     */
    public function getTypeOption()
    {
        return $this->typeOption;
    }

    /**
     * 回傳商品類別(type)
     * @param int $type 類別
     * @return object 回傳商品類別(type)
     */
    public function getProductType($type)
    {
        $typeData = (object) ['id' => 0, 'name' => ''];
        foreach ($this->typeOption as $value) {
            if ($value->id === $type) {
                $typeData = $value;
                break;
            }
        }

        return $typeData;
    }
}
