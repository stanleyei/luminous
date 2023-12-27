<!-- 前台購物車頁 -->

<script setup>
import { usePage } from '@inertiajs/vue3';

const successfulBidProducts = computed(() => usePage().props.successfulBidProducts ?? []);
</script>

<template>
  <div class="flex flex-col gap-y-4 md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] py-7 px-4">
    <!-- 訂單詳情區塊 -->
    <section>
      <h2 class="mb-4 py-1 bg-main-yellow font-bold text-xl text-center">訂單詳情</h2>
      <div v-if="successfulBidProducts.length" class="flex flex-col items-center gap-y-3">
        <figure
          v-for="bidProducts in successfulBidProducts"
          :key="bidProducts.id"
          class="flex items-center justify-between w-full py-3 md:pl-7 pl-4 md:pr-7 pr-2 rounded bg-gray-100/50 text-center break-keep"
        >
          <input type="checkbox" class="mr-7">
          <div class="flex md:gap-x-5 gap-x-3 flex-1">
            <img :src="bidProducts.cover_photo_path" alt="商品圖片" width="72" class="h-[72px] object-cover rounded-md">
            <div class="flex-1 md:text-base text-sm">
              <p class="flex items-center max-h-[40px] h-[40px] break-all">{{ bidProducts.name }}</p>
              <p class="text-start text-red-400 font-bold">NT$ {{ bidProducts.bid_price.toLocaleString() }}</p>
            </div>
          </div>
          <button type="button" class="p-2 rounded-lg bg-red-600 text-white md:text-base text-sm hover:bg-red-500 transition-colors">放棄商品</button>
        </figure>
      </div>
      <div v-else>尚無得標商品，前往競標</div>
    </section>

    <section>
      <h3 class="py-1 pl-7 bg-main-yellow font-bold text-lg">寄送地址</h3>
    </section>
  </div>
</template>

<style scoped>

</style>
