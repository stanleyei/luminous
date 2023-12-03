<!-- 捲軸滾動分頁組件 -->

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  paginationData: {
    type: Object,
    required: true,
    default: () => {
      return {
        data: [],
        total: 1,
        current_page: 1,
        per_page: 10,
      };
    },
  },
});

const observer = ref(null);
const container = ref(null);
const lastData = ref(null);
const pageCount = ref(0);

// 獲取資料
const getData = () => {
  const [url, param] = props.paginationData.first_page_url.split('?');
  const [pageKey] = param.split('=');
  const query = route().params;
  pageCount.value += 1;
  query[pageKey] = pageCount.value;
  router.get(url, query, {
    preserveState: true,
    preserveScroll: true,
  });
};

// 獲取最後一筆的dom元素
const getLastData = () => {
  if (!props.paginationData.data.length) return;
  lastData.value = container.value.lastElementChild;
};

// 初始化監聽器
const initObserve = () => {
  observer.value = new IntersectionObserver(handlerObserve);
  if (!lastData.value) return;
  observer.value.observe(lastData.value);
};

// 監聽器的回呼函式
const handlerObserve = async (entries) => {
  const [{ isIntersecting, target }] = entries;
  if (!isIntersecting) return;
  await getData();
  observer.value.unobserve(target);
};

// 獲取最後一筆的dom元素，並初始化監聽器
const initInfiniteScroll = () => {
  if (props.paginationData.data.length < props.paginationData.per_page) return;
  getLastData();
  initObserve();
};

// 註冊監聽器
onMounted(async () => {
  await getData();
  initInfiniteScroll();
});

// 更新資料時，重新註冊監聽器
onUpdated(() => {
  initInfiniteScroll();
});
</script>

<template>
  <section ref="container">
    <slot />
  </section>
</template>
