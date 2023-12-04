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
        Schema::create('user_client_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_client_id')->constrained('user_clients')->onDelete('cascade')->comment('使用者id');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('商品id');
            $table->unsignedTinyInteger('status')->default(1)->comment('狀態(1:加入拍賣,2:得標,3:未得標)');
            $table->unsignedInteger('bid_price')->comment('出價金額');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_client_product');
    }
};
