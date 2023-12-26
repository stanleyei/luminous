<!-- 前台產品卡片組件 -->

<script setup>
defineProps({
  product: Object,
});
</script>

<template>
  <Link :href="route('product.detail', { id: product.id })" :title="`前往${product.name}(跳轉頁面)`" class="grid md:text-base text-sm group">
    <figure class="grid row-span-4 grid-rows-[subgrid] gap-y-1 pb-2 bg-[#CCCAB1] text-main-swamp-green shadow-md">
      <div class=" overflow-hidden">
        <img :src="product.cover_photo_path" :alt="product.name" class="object-cover w-full aspect-square group-hover:scale-125 transition-transform duration-1000">
      </div>
      <figcaption class="flex justify-center items-center h-[48px] px-3 text-center">
        <p class="break-all line-clamp-2">{{ product.name }}</p>
      </figcaption>
      <time class="flex md:flex-row flex-col px-3">
        決標時間：
        <span>{{ product.end_time }}</span>
      </time>
      <div class="flex flex-col gap-1 px-3">
        <slot />
      </div>
    </figure>
  </Link>
</template>
