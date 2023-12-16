<!-- 前台模板 -->

<script setup>

</script>

<template>
  <section id="frontend-layout" class="max-w-3xl mx-auto bg-white">
    <FrontendHeader />

    <main class="pt-[65px] overflow-x-hidden">
      <slot />
    </main>

    <FrontendFooter />
  </section>
</template>
