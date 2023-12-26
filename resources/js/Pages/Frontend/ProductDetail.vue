<!-- 前台商品詳細頁 -->

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const user = computed(() => usePage().props.auth.user);
const rtData = computed(() => props.response?.rt_data ?? {});
const productData = computed(() => rtData.value.productData ?? {});
const selectedPhoto = computed(() => productData.value.photos[selectedPhotoIndex.value] ?? {});
const selectedPhotoIndex = ref(productData.value?.cover_photo_index ?? 0);
const showBidModal = ref(false);

// 決標剩餘時間倒數計時器
const countDown = () => {
  const endTime = new Date(productData.value.end_time).getTime();
  const nowTime = new Date().getTime();
  const time = endTime - nowTime;
  const days = Math.floor(time / (1000 * 60 * 60 * 24));
  const hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((time % (1000 * 60)) / 1000);

  const timeString = days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0
    ? '已結標'
    : `${padZero(hours)} : ${padZero(minutes)} : ${padZero(seconds)}`;

  return { days, timeString };
};

/**
 * 補零
 * @param {number} num 數字
 * @returns {string} 補零後的字串
 */
const padZero = (num) => {
  const str = String(num);
  return str.padStart(2, '0');
};

const { useAlert } = useAlertStore();

const openBidModal = () => {
  if (!user.value) {
    useAlert({
      type: 'warning',
      cancelContent: '',
      confirmContent: '前往登入',
      confirm: () => router.get(route('login')),
    });
    return;
  }

  if (user.value.role === 1) {
    useAlert({
      type: 'warning',
      content: '您是管理員，無法競標商品',
      cancelContent: '',
    });
    return;
  }

  showBidModal.value = true;
};

const countDownDays = ref(countDown().days);
const countDownTime = ref(countDown().timeString);

setInterval(() => {
  countDownDays.value = countDown().days;
  countDownTime.value = countDown().timeString;
  if (countDownTime.value === '已結標') {
    clearInterval();
  }
}, 1000);
</script>

<template>
  <div class="md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] pt-4 pb-7 px-4">
    <!-- 商品照片 -->
    <section class="flex flex-col gap-y-2 pb-6">
      <figure class="flex items-center md:h-[650px] h-[300px] bg-gray-100">
        <img :src="selectedPhoto.photo_path" :alt="selectedPhoto.photo_alt" class="h-full mx-auto">
      </figure>
      <aside class="custom-scroll flex gap-2 pb-2 overflow-x-auto">
        <button
          v-for="(photo, index) in productData?.photos ?? []"
          :key="photo.id"
          type="button"
          :class="twMerge('min-w-[72px] w-[72px] min-h-[72px] h-[72px] bg-gray-100 border-2 border-black/30 hover:border-black/50 transition-colors', selectedPhotoIndex === index && 'border-black')"
          @click="selectedPhotoIndex = index"
        >
          <img :src="photo.photo_path" :alt="photo.photo_alt" class="h-full mx-auto">
        </button>
      </aside>
    </section>

    <!-- 競標按鈕 -->
    <section class="pb-4">
      <button v-if="countDownTime !== '已結標'" type="button" class="w-full py-2 px-8 rounded-lg bg-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 transition-colors hover:bg-[#CCCAB1]" @click="openBidModal">
        我要競標
      </button>
      <Link v-else :href="route('shoppingCart.index')" class="inline-block w-full py-2 px-8 rounded-lg bg-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 text-center transition-colors hover:bg-[#CCCAB1]">
        前往結帳
      </Link>
    </section>
    <Teleport to="body">
      <BidModal :show="showBidModal" :product-data="productData" @close="showBidModal = false" />
    </Teleport>

    <!-- 倒數計時 -->
    <section class="flex items-center justify-center py-2 bg-main-swamp-green/80 md:text-7xl text-5xl text-white font-clockicons">
      <div v-if="countDownDays >= 0" class="flex items-end pr-2">
        <p>{{ countDownDays }}</p>
        <small class="text-base font-bold">天</small>
      </div>
      <p>{{ countDownTime }}</p>
    </section>

    <!-- 商品資訊 -->
    <section class="flex flex-col gap-4 py-4 px-3 border-x-4 border-b-4 border-main-swamp-green/80 md:text-lg text-main-swamp-green">
      <p>{{ productData.name }}</p>
      <div class="flex items-center gap-2">
        {{ countDownTime === '已結標' ? '最終得標價格' : '目前競標價格' }}：
        <b class="text-main-light-red">
          NT$ {{ productData.price.toLocaleString() }}
        </b>
      </div>
      <p class="wysiwyg-customize-block" v-html="productData.description"></p>
    </section>
  </div>
</template>
