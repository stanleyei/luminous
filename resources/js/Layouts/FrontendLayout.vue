<!-- 前台模板 -->

<script setup>

</script>

<template>
  <section id="frontend-layout" class="bg-white">
    <FrontendHeader />

    <main class="min-h-[100dvh] xl:pt-[100px] pt-[65px] overflow-x-hidden">
      <slot />
    </main>

    <FrontendFooter />
  </section>
</template>
