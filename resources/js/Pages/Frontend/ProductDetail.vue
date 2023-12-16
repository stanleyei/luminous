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

// 決標剩餘時間倒數計時器
const countDown = () => {
  const endTime = new Date(productData.value.end_time).getTime();
  const nowTime = new Date().getTime();
  const time = endTime - nowTime;
  const days = Math.floor(time / (1000 * 60 * 60 * 24));
  const hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((time % (1000 * 60)) / 1000);

  const timeString = days === 0 && hours === 0 && minutes === 0 && seconds === 0
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
  <div class="md:min-h-[calc(100dvh-201px)] min-h-[calc(100dvh-177px)] py-7 px-4">
    <!-- 商品照片 -->
    <section class="flex md:flex-row flex-col gap-x-2 gap-y-2">
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
    </section>

    <!-- 倒數計時 -->
    <section class="py-4">
      <div class="flex items-center justify-center text-5xl">
        <p class="font-clockicons">
          {{ countDownDays }}
          <small class="text-base">天</small>
          {{ countDownTime }}
        </p>
      </div>
    </section>

    <!-- 商品資訊 -->
    <section class="flex flex-col gap-4 md:ml-[97px] px-3 border-l-4 border-main-brown md:text-lg text-main-swamp-green">
      <p>{{ productData.name }}</p>
      <div class="flex items-center gap-2">
        目前競標價格：
        <b class="text-main-light-red">
          NT$ {{ productData.price.toLocaleString() }}
        </b>
      </div>
      <p class="wysiwyg-customize-block" v-html="productData.description"></p>
    </section>

    <!-- 競標按鈕 -->
    <section v-if="countDownTime !== '已結標'" class="flex justify-center pt-10">
      <button type="button" class="py-2 px-8 rounded-lg bg-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 transition-colors hover:bg-[#CCCAB1]">
        我要競標
      </button>
    </section>
  </div>
</template>
