<!-- 前台註冊登入導覽按鈕列 -->

<script setup>
import { usePage } from '@inertiajs/vue3';
import { twMerge } from 'tailwind-merge';

const pageComponent = computed(() => usePage().component ?? '');
</script>

<template>
  <nav class="grid grid-cols-2">
    <Link :href="route('register')" :class="twMerge('link-btn', pageComponent === 'Frontend/AuthRegister' && 'selected')">
      註冊會員
    </Link>
    <Link :href="route('login')" :class="twMerge('link-btn', pageComponent === 'Frontend/AuthLogin' && 'selected')">
      登入會員
    </Link>
  </nav>
</template>

<style scoped>
.link-btn {
  @apply py-4 text-center border-b-2 border-dashed border-main-swamp-green/80;
}

.selected {
  @apply border-transparent bg-main-swamp-green/80 text-white pointer-events-none;
}
</style>
