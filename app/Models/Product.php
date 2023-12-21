<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property int $id
 * @property int $type
 * @property string $name
 * @property bool $status
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property int $price
 * @property bool $featured
 * @property string $description
 * @property int $cover_photo_index
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|ProductPhoto[] $productPhotos
 * @property Collection|UserClient[] $userClients
 *
 * @package App\Models
 */
class Product extends Model
{
    protected $table = 'products';
    public static $snakeAttributes = false;

    protected $casts = [
        'type' => 'int',
        'status' => 'int',
        'price' => 'int',
        'featured' => 'int',
        'cover_photo_index' => 'int'
    ];

    protected $fillable = [
        'type',
        'name',
        'status',
        'start_time',
        'end_time',
        'price',
        'featured',
        'description',
        'cover_photo_index',
    ];

    public function productPhotos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function userClients()
    {
        return $this->belongsToMany(UserClient::class, 'user_client_product')
                    ->withPivot('id', 'status', 'bid_price')
                    ->withTimestamps();
    }

    public function userClientProducts()
    {
        return $this->hasMany(UserClientProduct::class);
    }

    // 獲取前台商品列表資料
    public function scopeActive($query)
    {
        return $query
            ->where('status', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    /**
     * 獲取商品封面照片
     * @return ProductPhoto
     */
    public function scopeCoverPhoto()
    {
        return $this->productPhotos[$this->cover_photo_index] ?? $this->productPhotos->first();
    }

    /**
     * 獲取目前競標最高價格
     * @return int
     */
    public function scopeHeightestPrice()
    {
        return $this->userClients->max('pivot.bid_price') ?? $this->price;
    }
}
