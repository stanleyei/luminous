<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '管理者',
            'email' => 'admin@gmail.com',
            'role' => 1,
            'email_verified_at' => now(),
            'password' => '12345678',
            'remember_token' => Str::random(10),
        ]);
    }
}
