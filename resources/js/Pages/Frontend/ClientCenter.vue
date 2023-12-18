<!-- 前台會員中心頁面 -->

<script setup>
import { twMerge } from 'tailwind-merge';
import iconMail from '/images/icon/icon-mail.svg';
import iconPhone from '/images/icon/icon-phone.svg';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const selectedStatus = ref(1);
const userClientData = computed(() => rtData.value.userClientData ?? {});
const productData = computed(() => {
  const data = rtData.value.clientProducts ?? [];
  return data.filter(item => item.pivot_status === selectedStatus.value);
});
</script>

<template>
  <div class="relative flex flex-col gap-y-4 md:min-h-[calc(100dvh-201px)] min-h-[calc(100dvh-177px)] py-7 px-4 text-main-swamp-green">
    <section class="py-4 border-b-2 border-dashed border-main-swamp-green/80">
      <h2 class="font-bold text-lg pb-3">會員資訊</h2>
      <article class="flex flex-col gap-2">
        <div class="flex gap-2 items-center">
          <img :src="iconMail" alt="信件圖示" width="16" class="mt-1">
          <p>{{ userClientData.email }}</p>
        </div>
        <div class="flex gap-2 items-center">
          <img :src="iconPhone" alt="電話圖示" width="16" class="mt-1">
          <p>{{ userClientData.phone }}</p>
        </div>
      </article>
    </section>

    <section class="flex justify-between items-center">
      <h2 class="font-bold text-lg">
        {{ selectedStatus === 1 ? '競標中商品' : '已得標商品' }}
        <span>(共{{ productData.length }}件)</span>
      </h2>
      <select v-model="selectedStatus" class="rounded-xl cursor-pointer">
        <option :value="1">競標中</option>
        <option :value="2">已得標</option>
      </select>
    </section>

    <!-- 商品列表 -->
    <section :class="twMerge('grid md:grid-cols-3 grid-cols-2 gap-4 mx-auto py-5', !productData?.length && 'grid-cols-1')">
      <ProductCard v-for="product in productData ?? []" :key="product.id" :product="product" />
      <p v-show="!productData?.length" class="absolute top-1/2 left-1/2 -translate-x-1/2 md:text-3xl text-xl">查無商品資料</p>
    </section>
  </div>
</template>
