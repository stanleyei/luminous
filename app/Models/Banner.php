<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Banner
 *
 * @property int $id
 * @property string $photo_path
 * @property string $photo_alt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Banner extends Model
{
    protected $table = 'banners';
    public static $snakeAttributes = false;

    protected $fillable = [
        'photo_path',
        'photo_alt',
    ];
}
