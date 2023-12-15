<!-- 前台頁首組件 -->

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import logoLuminous from '/images/logo/logo-luminous.png';
import iconShoppingCart from '/images/icon/icon-shopping-cart.svg';
import iconUser from '/images/icon/icon-user.svg';
import iconSearch from '/images/icon/icon-search.svg';

const user = computed(() => usePage().props.auth.user);
const showNavigationDropdown = ref(false);
const showSearchBar = ref(false);
const keywords = ref('');

// 監聽組件變化，關閉選單
watch(() => usePage().component, () => {
  showNavigationDropdown.value = false;
});

// 搜尋關鍵字
const searchKeywords = () => {
  router.get(route('product.index'), { q: keywords.value }, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <header class="fixed z-[2] top-0 left-0 w-full xl:h-[100px] h-[65px] px-3 bg-main-yellow shadow-md">
    <Transition name="fade" mode="out-in">
      <div v-if="!showSearchBar" class="container relative flex items-center h-full mx-auto">
        <h1 class="pr-4">
          <Link href="/" title="回到首頁(跳轉頁面)">
            <img :src="logoLuminous" alt="LOGO" width="200" class="xl:w-[200px] w-[150px]">
          </Link>
        </h1>
        <section class="flex xl:flex-col gap-y-1 flex-1 xl:items-start items-center justify-end h-full xl:pt-3">
          <div class="flex items-center xl:gap-7 gap-3 xl:self-end xl:mr-0 mr-5">
            <div class="flex items-center gap-2">
              <label class="xl:block hidden">
                <input v-model="keywords" type="text" placeholder="搜尋商品" class="w-[200px] py-1 border border-gray-300 rounded-md px-3">
              </label>
              <button type="button" class="xl:block hidden" @click="searchKeywords">
                <img :src="iconSearch" alt="放大鏡圖示" width="22" height="22" class="w-[22px]">
              </button>
              <button type="button" class="xl:hidden block" @click="showSearchBar = !showSearchBar">
                <img :src="iconSearch" alt="放大鏡圖示" width="22" height="22" class="w-[22px]">
              </button>
            </div>
            <Link v-if="!user" href="" title="(跳轉頁面)前往登入">
              <img :src="iconUser" alt="使用者圖示" width="22" height="22" class="w-[22px]">
            </Link>
            <button type="button">
              <img :src="iconShoppingCart" alt="購物車圖示" width="22" height="22" class="w-[22px]">
            </button>
          </div>

          <!-- 導覽列 -->
          <FrontendHeaderNav class="xl:flex hidden" />

          <!-- 行動版選單按鈕 -->
          <button type="button" class="xl:hidden w-[60px] h-full bg-gray-200/50" @click="showNavigationDropdown = !showNavigationDropdown">
            <svg class="h-6 w-6 mx-auto" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <title>導覽列選單</title>
              <path :class="{ hidden: showNavigationDropdown, 'inline-flex': !showNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ hidden: !showNavigationDropdown, 'inline-flex': showNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <Transition name="fade" mode="out-in">
            <FrontendHeaderNav v-show="showNavigationDropdown" class="absolute top-[70px] right-0 xl:hidden flex xl:flex-row flex-col rounded-lg bg-white shadow-lg" />
          </Transition>
        </section>
      </div>
      <div v-else class="flex items-center gap-4 w-full h-full">
        <button type="button" @click="searchKeywords">
          <img :src="iconSearch" alt="放大鏡圖示" width="22" height="22" class="w-[22px]">
        </button>
        <label class="flex-1">
          <input v-model="keywords" type="text" placeholder="搜尋商品" class="w-full py-1 border border-gray-300 rounded-md px-3">
        </label>
        <button type="button" @click="showSearchBar = !showSearchBar">
          <svg class="h-6 w-6 mx-auto" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <title>導覽列選單</title>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </Transition>
  </header>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  @apply transition-opacity;
}

.fade-enter-from,
.fade-leave-to {
  @apply opacity-0;
}
</style>
