<!-- 前台商品列表頁 -->

<script setup>
import { router } from '@inertiajs/vue3';
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const paginationData = computed(() => rtData.value.productData ?? {});
const productTypeOption = computed(() => rtData.value.productTypeOption ?? []);
const currentType = computed(() => rtData.value.currentType ?? 0);

/**
 * 更換商品種類
 * @param {number} type 商品種類
 */
const changeType = (type) => {
  router.get(route('product.index'), { type }, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <div class="flex flex-col gap-y-4 justify-between md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] py-7 px-4">
    <div>
      <section class="flex justify-between items-center">
        <h2 class="font-bold text-xl">商品列表</h2>
        <select class="rounded-xl cursor-pointer" @change="(e) => changeType(Number(e.target.value))">
          <option value="" :selected="!currentType">全部商品</option>
          <option
            v-for="typeOption in productTypeOption"
            :key="typeOption.id"
            :value="typeOption.id"
            :selected="currentType === typeOption.id"
          >
            {{ typeOption.name }}
          </option>
        </select>
      </section>

      <!-- 商品列表 -->
      <section :class="twMerge('grid md:grid-cols-3 grid-cols-2 gap-4 mx-auto py-5', !paginationData?.data?.length && 'grid-cols-1')">
        <ProductCard v-for="product in paginationData?.data ?? []" :key="product.id" :product="product" />
        <p v-show="!paginationData?.data?.length" class="md:text-3xl text-xl">查無商品資料</p>
      </section>
    </div>

    <!-- 分頁器 -->
    <section class="flex justify-center">
      <Pagination :pagination-data="paginationData" />
    </section>
  </div>
</template>
