<!-- 前台首頁 -->

<script setup>
import iconAngleLeft from '/images/icon/icon-angle-left.svg';
import iconAngleRight from '/images/icon/icon-angle-right.svg';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const bannerData = computed(() => rtData.value?.banners ?? []);
const productData = computed(() => rtData.value?.products ?? []);

// swiper前後按鈕
const btnPrevBanner = ref(null);
const btnNextBanner = ref(null);
</script>

<template>
  <div>
    <!-- 橫幅區塊 -->
    <div v-if="bannerData.length" class="relative group cursor-grab">
      <Swiper
        v-slot="{ slide }"
        :slide-data="bannerData"
        :slides-per-view="1"
        :space-between="0"
        :btn-prev="btnPrevBanner"
        :btn-next="btnNextBanner"
        autoplay
      >
        <img :src="slide.photo_path" :alt="slide.photo_alt" class="w-full aspect-[1900/728] object-cover">
      </Swiper>
      <div class="absolute z-[1] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 hidden justify-between w-full px-4 group-hover:lg:flex">
        <button ref="btnPrevBanner" type="button" class="banner-action-btn">
          <img :src="iconAngleLeft" alt="向左箭頭圖示" width="40" class="banner-action-btn-icon">
        </button>
        <button ref="btnNextBanner" type="button" class="banner-action-btn">
          <img :src="iconAngleRight" alt="向左箭頭圖示" width="40" class="banner-action-btn-icon">
        </button>
      </div>
    </div>

    <!-- 產品區塊 -->
    <div class="container grid grid-cols-2 xl:gap-6 gap-4 mx-auto py-10 px-3">
      <Link v-for="product in productData" :key="product.id" href="">
        <figure class="">
          <img :src="product.cover_photo_path" :alt="product.name" class="w-full">
          <figcaption class="text-center">{{ product.name }}</figcaption>
          <div class="text-center">NT${{ product.price }}</div>
        </figure>
      </Link>
    </div>
  </div>
</template>

<style scoped>
.banner-action-btn {
  @apply flex items-center justify-center w-[4.17vw] h-[4.17vw] rounded-full bg-gray-300/60 hover:bg-gray-300 transition-colors;
}

.banner-action-btn-icon {
  @apply w-[2.6vw];
}
</style>
