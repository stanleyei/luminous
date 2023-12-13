<!-- 前台模板 -->

<script setup>

</script>

<template>
  <section id="frontend-layout" class="bg-white">
    <FrontendHeader />

    <div class="min-h-[100dvh] xl:pt-[100px] pt-[65px] px-3 overflow-x-hidden">
      <main class="container mx-auto">
        <slot />
      </main>
    </div>

    <FrontendFooter />
  </section>
</template>
