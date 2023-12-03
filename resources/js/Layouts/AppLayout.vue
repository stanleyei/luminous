<!-- 全域模板 -->

<script setup>
import { usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

// 判斷當前頁面應使用的模板(前台、後台、其他)
const layoutPosition = computed(() => {
  const { component } = usePage();
  const fileName = [
    { code: 'Frontend', comp: FrontendLayout },
    { code: 'Backend', comp: BackendLayout },
    { code: 'Auth', comp: GuestLayout },
  ];
  const layouut = fileName.find(({ code }) => component.includes(code));
  return layouut?.comp ?? GuestLayout;
});
</script>

<template>
  <section id="app-layout">
    <component :is="layoutPosition">
      <slot />
    </component>
  </section>
  <AlertModal />
</template>
