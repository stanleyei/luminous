<!-- 前台頁首組件 -->

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import logoLuminous from '/images/logo/logo-luminous.png';
import iconShoppingCart from '/images/icon/icon-shopping-cart.svg';
import iconUser from '/images/icon/icon-user.svg';
import iconSearch from '/images/icon/icon-search.svg';

const user = computed(() => usePage().props.auth.user);
const winBidProducts = computed(() => usePage().props.clientWinBidProductList ?? []);
const showNavigationDropdown = ref('');
const showSearchBar = ref(false);
const keywords = ref('');
const headerDom = ref(null);

const switchMenu = (menu) => {
  showNavigationDropdown.value = showNavigationDropdown.value === menu
    ? ''
    : menu;
};

// 監聽組件變化，關閉選單
watch(() => usePage().component, () => {
  showNavigationDropdown.value = '';
});

// 搜尋關鍵字
const searchKeywords = () => {
  router.get(route('product.index'), { q: keywords.value }, {
    preserveState: true,
    preserveScroll: true,
  });
};

/**
 * 顯示購物車
 */
const showShoppingCart = () => {
  if (!user.value || user.value?.role === 1) {
    router.get(route('login'));
    return;
  }
  switchMenu('shoppingCart');
};

onClickOutside(headerDom, () => {
  showNavigationDropdown.value = '';
  showSearchBar.value = false;
});
</script>

<template>
  <header ref="headerDom" class="fixed z-[2] top-0 max-w-3xl w-full h-[65px] px-3 bg-main-yellow shadow-md">
    <Transition name="fade" mode="out-in">
      <div v-if="!showSearchBar" class="relative flex items-center h-full mx-auto">
        <h1 class="pr-4">
          <Link href="/" title="回到首頁(跳轉頁面)" class="font-clockicons">
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
            <button type="button" class="relative" @click="showShoppingCart">
              <img :src="iconShoppingCart" alt="購物車圖示" width="22" height="22" class="w-[22px]">
              <div v-show="winBidProducts.length" class="absolute -top-[8px] -right-[12px] flex justify-center items-center w-[18px] h-[18px] bg-red-500 rounded-full text-white text-sm font-bold">
                {{ winBidProducts.length }}
              </div>
            </button>
          </div>

          <!-- 行動版選單按鈕 -->
          <button type="button" class="w-[60px] h-full bg-gray-200/50" @click="switchMenu('nav')">
            <svg class="h-6 w-6 mx-auto" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <title>導覽列選單</title>
              <path :class="{ hidden: showNavigationDropdown === 'nav', 'inline-flex': showNavigationDropdown !== 'nav' }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ hidden: showNavigationDropdown !== 'nav', 'inline-flex': showNavigationDropdown === 'nav' }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- 購物車已得標產品視窗 -->
          <Transition name="fade" mode="out-in">
            <nav v-show="showNavigationDropdown === 'shoppingCart'" class="absolute top-[70px] right-0 p-3 rounded-lg bg-white shadow-lg">
              <div class="triangle-decoration"></div>
              <div v-if="winBidProducts.length" class="flex flex-col items-center gap-y-3">
                <Link
                  v-for="bidProducts in winBidProducts"
                  :key="bidProducts.id"
                  :href="route('product.detail', { id: bidProducts.id })"
                  title="前往付款(跳轉頁面)"
                  class="flex items-center gap-2 w-[300px] p-1 rounded bg-gray-100 text-center break-keep hover:bg-gray-200 transition-colors"
                >
                  <img :src="bidProducts.cover_photo_path" alt="商品圖片" width="72" class="h-[72px] object-cover rounded-md">
                  <div class="flex-1 text-sm">
                    <p class="text-start text-red-400 font-bold">已得標</p>
                    <p class="flex items-center justify-center max-h-[40px] h-[40px] break-all">{{ bidProducts.name }}</p>
                    <p class="text-end text-red-400 font-bold">NT$ {{ bidProducts.bid_price.toLocaleString() }}</p>
                  </div>
                </Link>
              </div>
              <div v-else>尚無得標商品</div>

              <!-- 結帳/競標按鈕 -->
              <Link :href="route(winBidProducts.length ? 'shoppingCart.index' : 'product.index')" class="block w-full mt-3 py-1 px-3 rounded-lg bg-main-swamp-green/80 text-white text-center hover:bg-main-swamp-green/90 transition-colors">
                {{ winBidProducts.length ? '前往結帳' : '前往競標' }}
              </Link>
            </nav>
          </Transition>

          <!-- 導覽列選單 -->
          <Transition name="fade" mode="out-in">
            <nav v-show="showNavigationDropdown === 'nav'" class="absolute top-[70px] right-0 flex flex-col items-center gap-y-3 gap-x-5 p-3 rounded-lg bg-white shadow-lg">
              <Link v-if="!user" :href="route('login')" title="前往登入(跳轉頁面)" class="nav-link">
                登入
              </Link>
              <Link :href="route('product.index')" title="前往所有商品(跳轉頁面)" class="nav-link" @click="showNavigationDropdown = ''">
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
      </div>
    </Transition>
  </header>
</template>

<style scoped>
.nav-link {
  @apply inline-block w-full py-1 px-3 rounded text-center break-keep hover:bg-gray-200/50 transition-colors;
}

.triangle-decoration {
  @apply absolute -top-[14px] right-[78px] w-0 h-0 border-x-[14px] border-t-0 border-b-[14px] border-[transparent_transparent_#fff_transparent];
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
