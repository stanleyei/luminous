<!-- 輪播 -->

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, A11y, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/a11y';

const props = defineProps({
  slideData: {
    type: Array,
    required: true,
    default: () => [],
  },
  slidesPerView: {
    type: Number,
    required: false,
    default: 3,
  },
  spaceBetween: {
    type: Number,
    required: false,
    default: 50,
  },
  autoplay: {
    type: Boolean,
  },
  paginationEl: {
    type: Object,
    required: false,
    default: () => null,
  },
  bulletClass: {
    type: String,
    required: false,
    default: '',
  },
  bulletActiveClass: {
    type: String,
    required: false,
    default: 'swiper-pagination-bullet-active',
  },
  btnPrev: {
    type: Object,
    required: false,
    default: () => null,
  },
  btnNext: {
    type: Object,
    required: false,
    default: () => null,
  },
  slideClass: {
    type: String,
    required: false,
    default: '',
  },
});

const modules = [Navigation, Pagination, A11y, Autoplay];
const onSwiper = (swiper) => {
  nextTick(() => {
    swiper.params.pagination.el = props.paginationEl;
    swiper.params.pagination.renderBullet = (index, className) => {
      const active = !index ? props.bulletActiveClass : '';
      return `<button type="button" class="${className} ${active} ${props.bulletClass}"><span class="sr-only">輪播導覽按鈕</span></button>`;
    },
    swiper.params.navigation.prevEl = props.btnPrev;
    swiper.params.navigation.nextEl = props.btnNext;
    swiper.navigation.init();
    swiper.pagination.init();
    swiper.pagination.render();
  });
};
</script>

<template>
  <Swiper
    class="vue-swiper"
    :modules="modules"
    :slides-per-view="props.slidesPerView"
    :space-between="props.spaceBetween"
    :autoplay="props.autoplay && {
      delay: 5000,
      disableOnInteraction: false,
    }"
    :pagination="{
      enabled: !!props.paginationEl,
      bulletActiveClass: props.bulletActiveClass,
      clickable: true,
    }"
    loop
    navigation
    centered-slides
    centered-slides-bounds
    @init="onSwiper"
  >
    <SwiperSlide v-for="(slide, index) in props.slideData" :key="index" :class="props.slideClass">
      <slot :slide="slide" :index="index" />
    </SwiperSlide>
  </Swiper>
</template>

<style lang="scss" scoped>
.vue-swiper {
  @apply w-full;
}
</style>
