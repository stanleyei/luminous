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

// 關閉搜尋欄
const closeSearchBar = () => {
  showSearchBar.value = false;
  keywords.value = '';
};
</script>

<template>
  <header class="fixed z-[2] top-0 left-1/2 -translate-x-1/2 max-w-3xl w-full h-[65px] px-3 bg-main-yellow shadow-md">
    <Transition name="fade" mode="out-in">
      <div v-if="!showSearchBar" class="relative flex items-center h-full mx-auto">
        <h1 class="pr-4">
          <Link href="/" title="回到首頁(跳轉頁面)">
            <img :src="logoLuminous" alt="LOGO" width="200" class="w-[150px]">
          </Link>
        </h1>
        <section class="flex gap-y-1 flex-1 items-center justify-end h-full">
          <div class="flex items-center gap-3 mr-5">
            <div class="flex items-center gap-2">
              <button type="button" @click="showSearchBar = !showSearchBar">
                <img :src="iconSearch" alt="放大鏡圖示" width="22" height="22" class="w-[22px]">
              </button>
            </div>
            <Link :href="route('client.index')" title="(跳轉頁面)前往會員中心">
              <img :src="iconUser" alt="使用者圖示" width="22" height="22" class="w-[22px]">
            </Link>
            <button type="button">
              <img :src="iconShoppingCart" alt="購物車圖示" width="22" height="22" class="w-[22px]">
            </button>
          </div>

          <!-- 行動版選單按鈕 -->
          <button type="button" class="w-[60px] h-full bg-gray-200/50" @click="showNavigationDropdown = !showNavigationDropdown">
            <svg class="h-6 w-6 mx-auto" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <title>導覽列選單</title>
              <path :class="{ hidden: showNavigationDropdown, 'inline-flex': !showNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ hidden: !showNavigationDropdown, 'inline-flex': showNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <Transition name="fade" mode="out-in">
            <nav v-show="showNavigationDropdown" class="absolute top-[70px] right-0 flex flex-col items-center gap-y-3 gap-x-5 p-3 rounded-lg bg-white shadow-lg">
              <Link :href="route('product.index')" title="前往所有商品(跳轉頁面)" class="nav-link" @click="showNavigationDropdown = !showNavigationDropdown">
                所有商品
              </Link>
              <a href="/" title="前往Instagram(另開新頁)" class="nav-link">Instagram</a>
              <Link v-if="user" :href="route('logout')" class="nav-link" type="button" method="post" as="button">
                登出
              </Link>
            </nav>
          </Transition>
        </section>
      </div>
      <div v-else class="flex items-center gap-4 w-full h-full">
        <button type="button" @click="searchKeywords">
          <img :src="iconSearch" alt="放大鏡圖示" width="22" height="22" class="w-[22px]">
        </button>
        <label class="flex-1">
          <input v-model="keywords" type="search" placeholder="搜尋商品" class="w-full py-1 border border-gray-300 rounded-md px-3" @keydown.enter="searchKeywords" @search="searchKeywords">
        </label>
        <button type="button" @click="closeSearchBar">
          <svg class="h-6 w-6 mx-auto" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <title>關閉搜尋欄</title>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </Transition>
  </header>
</template>

<style scoped>
.nav-link {
  @apply inline-block w-full py-1 px-3 rounded text-center break-keep hover:bg-gray-200/50 transition-colors;
}

.fade-enter-active,
.fade-leave-active {
  @apply transition-opacity;
}

.fade-enter-from,
.fade-leave-to {
  @apply opacity-0;
}
</style>
