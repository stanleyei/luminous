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
        Schema::table('user_client_product', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_client_product', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->comment('狀態(1:加入拍賣,2:得標,3:未得標)');
        });
    }
};
