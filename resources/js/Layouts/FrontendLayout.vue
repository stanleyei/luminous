<!-- 前台模板 -->

<script setup>

</script>

<template>
  <section id="frontend-layout" class="bg-white">
    <div class="relative container mx-auto">
      <FrontendHeader />

      <main class="min-h-[100dvh] overflow-x-hidden">
        <slot />
      </main>
    </div>
  </section>
</template>
