<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserClient
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class UserClient extends Model
{
    protected $table = 'user_clients';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $fillable = [
        'user_id',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'user_client_product')
                    ->withPivot('id', 'bid_price')
                    ->withTimestamps();
    }
}
