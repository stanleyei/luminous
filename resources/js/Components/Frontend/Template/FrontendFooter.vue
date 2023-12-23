<!-- 前台頁尾組件 -->

<script setup>
import logoIgQrcode from '/images/logo/logo-ig-qrcode.jpg';
</script>

<template>
  <footer class="py-8 md:pl-7 pl-4 pr-3 bg-main-yellow">
    <div class="container flex items-center justify-between gap-6 mx-auto md:pr-10">
      <div class="lg:text-base text-xs">
        <div class="flex md:gap-4 gap-2 text-main-swamp-green">
          <b>官方Line</b>
          <p>@Luminous2023</p>
        </div>
        <div class="flex md:gap-4 gap-2">
          <b>信箱</b>
          <a href="mailto:Luminous2023@gmail.com">Luminous2023@gmail.com</a>
        </div>
        <div class="flex md:gap-4 gap-2">
          <b>IG</b>
          <a href="/" title="前往Instagram(另開新頁)">Luminous.acc2023</a>
        </div>
      </div>
      <div class="flex gap-2">
        <img :src="logoIgQrcode" alt="Instagram網址" width="72" class="md:w-[100px] w-[72px]">
      </div>
    </div>
  </footer>
</template>
