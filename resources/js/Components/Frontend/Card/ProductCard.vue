<!-- 前台產品卡片組件 -->

<script setup>
defineProps({
  product: Object,
});
</script>

<template>
  <Link :href="route('product.detail', { id: product.id })" :title="`前往${product.name}(跳轉頁面)`">
    <figure class="flex flex-col gap-y-1 pb-2 bg-[#CCCAB1] text-[#333111] shadow-md">
      <img :src="product.cover_photo_path" :alt="product.name" class="object-cover w-full h-[210px]">
      <figcaption class="text-center">{{ product.name }}</figcaption>
      <b class="block text-center">NT${{ product.price.toLocaleString() }}</b>
    </figure>
  </Link>
</template>
