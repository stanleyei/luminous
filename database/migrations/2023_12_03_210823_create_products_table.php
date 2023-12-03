<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('產品名稱');
            $table->boolean('status')->default(1)->comment('狀態(0:下架,1:上架)');
            $table->dateTime('start_time')->comment('開始時間');
            $table->dateTime('end_time')->comment('結束時間');
            $table->unsignedInteger('price')->default(0)->comment('產品價格');
            $table->text('description')->default('')->comment('產品描述');
            $table->unsignedTinyInteger('cover_photo_index')->default(0)->comment('封面照片索引');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
