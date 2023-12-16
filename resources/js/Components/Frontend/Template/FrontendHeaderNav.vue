<!-- 前台頁首導覽列 -->

<template>
  <nav class="items-center gap-y-3 gap-x-5 p-3">
    <Link :href="route('product.index')" title="前往所有商品(跳轉頁面)" class="nav-link">所有商品</Link>
    <a href="/" title="前往Instagram(另開新頁)" class="nav-link">Instagram</a>
  </nav>
</template>

<style scoped>
.nav-link {
  @apply inline-block w-full py-1 px-3 rounded text-center break-keep hover:bg-gray-200/50 transition-colors;
}
</style>
