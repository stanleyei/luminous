<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserClientProduct
 *
 * @property int $id
 * @property int $user_client_id
 * @property int $product_id
 * @property int $status
 * @property int $bid_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Product $product
 * @property UserClient $userClient
 *
 * @package App\Models
 */
class UserClientProduct extends Model
{
    protected $table = 'user_client_product';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_client_id' => 'int',
        'product_id' => 'int',
        'status' => 'int',
        'bid_price' => 'int'
    ];

    protected $fillable = [
        'user_client_id',
        'product_id',
        'status',
        'bid_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function userClient()
    {
        return $this->belongsTo(UserClient::class);
    }
}
