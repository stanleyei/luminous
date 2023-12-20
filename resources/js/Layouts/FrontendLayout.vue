<!-- 前台模板 -->

<script setup></script>

<template>
  <section id="frontend-layout" class="h-[100dvh] overflow-x-hidden">
    <div class="max-w-3xl mx-auto bg-white">
      <FrontendHeader />

      <main class="min-h-[calc(100dvh-136px)] pt-[65px]">
        <slot />
      </main>

      <FrontendFooter />
    </div>
  </section>
</template>
