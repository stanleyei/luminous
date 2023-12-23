<!-- 前台模板 -->

<script setup>
import { usePage } from '@inertiajs/vue3';

const frontendLayout = ref(null);

/**
 * 當每次更換頁面組件時，將頁面滾動至最上方
 */
watch(() => usePage().component, () => {
  if (frontendLayout.value) frontendLayout.value.scrollTop = 0;
});
</script>

<template>
  <section id="frontend-layout" ref="frontendLayout" class="h-[100dvh] overflow-x-hidden">
    <div class="max-w-3xl mx-auto bg-white">
      <FrontendHeader />

      <main class="min-h-[calc(100dvh-164px)] pt-[65px]">
        <slot />
      </main>

      <FrontendFooter />
    </div>
  </section>
</template>
