<!-- 後台側欄導覽組件 -->

<script setup>
import { menuLinks } from '@/Composables/useBackendRoute';
</script>

<template>
  <nav class="md:flex hidden flex-col gap-[18px] w-[260px] pt-[9px] bg-main-gray text-white overflow-y-auto">
    <ul class="flex flex-col text-lg text-gray-300 h-0 overflow-hidden transition-[height] duration-500">
      <li v-for="list in menuLinks" :key="list.id" class="hover:bg-main-light-gray hover:text-main-skin">
        <Link :href="route(list.path)" :title="`前往${list.name}(跳轉頁面)`" class="flex items-center gap-2 py-2 px-11">
          <div class="w-[6px] h-[6px] rounded-[50%] bg-current"></div>
          {{ list.name }}
        </Link>
      </li>
    </ul>
  </nav>
</template>
