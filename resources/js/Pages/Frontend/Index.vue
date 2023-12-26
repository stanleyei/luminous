<!-- 前台首頁 -->

<script setup>
import { usePage } from '@inertiajs/vue3';
import iconAngleLeft from '/images/icon/icon-angle-left.svg';
import iconAngleRight from '/images/icon/icon-angle-right.svg';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const bannerData = computed(() => rtData.value?.banners ?? []);
const productData = computed(() => rtData.value?.products ?? []);
const flashMessage = computed(() => usePage().props?.flash?.message);

// swiper前後按鈕
const btnPrevBanner = ref(null);
const btnNextBanner = ref(null);

// 設定確認彈跳視窗參數
const { useAlert } = useAlertStore();
watch(
  () => flashMessage.value,
  () => {
    if (!flashMessage.value?.rt_code) return;
    useAlert({
      type: 'success',
      content: '已成功註冊',
    });
  },
  { immediate: true },
);
</script>

<template>
  <div class="@container">
    <!-- 橫幅區塊 -->
    <section v-if="bannerData.length" class="relative group cursor-grab">
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
    </section>

    <!-- 關於我們 -->
    <section class="flex flex-col items-center gap-1 pt-10 font-bold text-main-swamp-green">
      <h2>-關於我們-</h2>
      <p>Luminous是發光的意思，希望能</p>
      <p>讓每個戴上我們飾品的你/妳，都能</p>
      <p>像光一樣閃亮。</p>
    </section>

    <!-- 推薦商品 -->
    <section class="pt-10 px-4 text-main-swamp-green">
      <h2 class="flex justify-center mb-5 font-bold">-推薦-</h2>
      <div class="grid @3xl:grid-cols-3 grid-cols-2 gap-4 mx-auto">
        <ProductCard v-for="product in productData" :key="product.id" :product="product">
          <b>起標價：NT$ {{ product.price.toLocaleString() }}</b>
        </ProductCard>
      </div>
      <div class="flex justify-end">
        <Link :href="route('product.index')" title="前往商品列表頁(跳轉頁面)" class="pt-2 pb-1 px-1 border-b border-[#CCCAB1] text-sm hover:border-b-2">
          點我查看更多 →
        </Link>
      </div>
    </section>

    <Character />
  </div>
</template>

<style scoped>
.banner-action-btn {
  @apply flex items-center justify-center w-[2.17vw] h-[2.17vw] rounded-full bg-gray-300/60 hover:bg-gray-300 transition-colors;
}

.banner-action-btn-icon {
  @apply w-[1.4vw];
}
</style>
