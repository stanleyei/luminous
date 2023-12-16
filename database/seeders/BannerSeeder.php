<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            [
                'photo_path' => '/upload/banner/170244158306409663226af2f3114485aa4e0a23b4.webp',
                'photo_alt' => 'Banner圖片',
            ],
            [
                'photo_path' => '/upload/banner/1702441573eecca5b6365d9607ee5a9d336962c534.webp',
                'photo_alt' => 'Banner圖片',
            ],
            [
                'photo_path' => '/upload/banner/17026962019872ed9fc22fc182d371c3e9ed316094.webp',
                'photo_alt' => 'Banner圖片',
            ],
        ];

        foreach ($arr as $value) {
            Banner::create($value);
        }
    }
}
