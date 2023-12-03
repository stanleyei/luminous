<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPhoto
 *
 * @property int $id
 * @property int $product_id
 * @property string $photo_path
 * @property string $photo_alt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Product $product
 *
 * @package App\Models
 */
class ProductPhoto extends Model
{
    protected $table = 'product_photos';
    public static $snakeAttributes = false;

    protected $casts = [
        'product_id' => 'int'
    ];

    protected $fillable = [
        'product_id',
        'photo_path',
        'photo_alt',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
