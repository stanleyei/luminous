<!-- 前台會員中心頁面 -->

<script setup>
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const selectedStatus = ref(1);
const productData = computed(() => {
  const data = rtData.value.clientProducts ?? [];
  return data.filter(item => item.pivot_status === selectedStatus.value);
});
</script>

<template>
  <div class="flex flex-col gap-y-4 md:min-h-[calc(100dvh-201px)] min-h-[calc(100dvh-177px)] py-7 px-4">
    <section class="flex justify-between items-center">
      <h2 class="font-bold text-xl">{{ selectedStatus === 1 ? '競標中商品' : '已得標商品' }}</h2>
      <select v-model="selectedStatus" class="rounded-xl cursor-pointer">
        <option :value="1">競標中</option>
        <option :value="2">已得標</option>
      </select>
    </section>

    <!-- 商品列表 -->
    <section :class="twMerge('grid grid-cols-2 gap-4 mx-auto py-5', !productData?.length && 'grid-cols-1')">
      <ProductCard v-for="product in productData ?? []" :key="product.id" :product="product" />
      <p v-show="!productData?.length" class="md:text-3xl text-xl">查無商品資料</p>
    </section>
  </div>
</template>
