<!-- 後台模板 -->

<script setup>
import { usePage } from '@inertiajs/vue3';

const main = ref(null);

/**
 * 當每次更換頁面組件時，將頁面滾動至最上方
 */
watch(() => usePage().component, () => {
  if (main.value) main.value.scrollTop = 0;
});
</script>

<template>
  <div class="h-[100dvh] bg-gray-100 overflow-hidden">
    <BackendHeader />
    <section id="aside-main">
      <BackendAside />
      <main ref="main" id="main">
        <Breadcrumb class="max-w-7xl w-full mx-auto pl-3" />
        <slot />
      </main>
    </section>
  </div>
</template>

<style scoped>
#aside-main {
  @apply flex w-full h-[calc(100dvh-65px)];
}

#main {
  @apply relative flex-1 h-full sm:py-8 py-4 overflow-y-auto;
}
</style>
