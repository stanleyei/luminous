<!-- 後台側欄導覽組件 -->

<script setup>
import { menuLinks } from '@/Composables/useBackendRoute';
</script>

<template>
  <nav class="md:flex hidden flex-col gap-[18px] w-[220px] pt-[9px] bg-main-gray text-white overflow-y-auto">
    <ul class="flex flex-col text-xl">
      <li v-for="list in menuLinks" :key="list.id">
        <Link :href="route(list.path)" :title="`前往${list.name}(跳轉頁面)`" class="flex items-center gap-4 w-full py-3 px-7 hover:bg-main-light-gray hover:text-main-skin">
          <div class="w-[6px] h-[6px] rounded-[50%] bg-current"></div>
          {{ list.name }}
        </Link>
      </li>
    </ul>
  </nav>
</template>
