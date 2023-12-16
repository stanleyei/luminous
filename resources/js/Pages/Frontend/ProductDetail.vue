<!-- 前台商品詳細頁 -->

<script setup>
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const productData = computed(() => rtData.value.productData ?? {});
const selectedPhoto = computed(() => productData.value.photos[selectedPhotoIndex.value] ?? {});
const selectedPhotoIndex = ref(productData.value?.cover_photo_index ?? 0);
</script>

<template>
  <div class="md:min-h-[calc(100dvh-201px)] min-h-[calc(100dvh-177px)] py-7 px-4">
    <div class="flex md:flex-row flex-col gap-x-4 gap-y-2 md:mb-4 mb-8">
      <aside class="custom-scroll flex md:flex-col md:order-none order-1 gap-2 md:min-w-[89px] md:h-[500px] pb-2 md:overflow-y-auto md:overflow-x-visible overflow-x-auto">
        <button
          v-for="(photo, index) in productData?.photos ?? []"
          :key="photo.id"
          type="button"
          :class="twMerge('min-w-[72px] w-[72px] min-h-[72px] h-[72px] border-2 border-black/30 hover:border-black/50 transition-colors', selectedPhotoIndex === index && 'border-black')"
          @click="selectedPhotoIndex = index"
        >
          <img :src="photo.photo_path" :alt="photo.photo_alt" class="w-full">
        </button>
      </aside>
      <figure class="flex items-center md:h-[500px] h-[250px] bg-gray-100">
        <img :src="selectedPhoto.photo_path" :alt="selectedPhoto.photo_alt">
      </figure>
    </div>
    <article class="flex flex-col gap-4 md:ml-[86px] px-3 border-l-4 border-main-brown text-lg">
      <p>{{ productData.name }}</p>
      <b>NT${{ productData.price.toLocaleString() }}</b>
      <p class="wysiwyg-customize-block" v-html="productData.description"></p>
    </article>
    <div class="flex justify-center pt-10">
      <button type="button" class="py-2 px-10 rounded bg-main-light-blue md:text-xl text-lg font-bold text-white transition-colors hover:bg-main-blue">
        我要競標
      </button>
    </div>
  </div>
</template>
