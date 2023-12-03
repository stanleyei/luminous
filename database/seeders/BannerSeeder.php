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
                'photo_path' => '',
                'photo_alt' => '',
            ],
            [
                'photo_path' => '',
                'photo_alt' => '',
            ],
            [
                'photo_path' => '',
                'photo_alt' => '',
            ],
        ];

        foreach ($arr as $value) {
            Banner::create($value);
        }
    }
}
