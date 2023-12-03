<!-- 後台表格表頭排序按鈕組件 -->

<script setup>
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  sortKey: {
    type: String,
    required: true,
  },
});
const { sendParams } = useParamsStore();

// 是否為當前排序欄位且為升冪排序
const isActive = computed(() => {
  return useParamsStore().sortKey[props.sortKey] === '1';
});

const changeSort = () => {
  const value = isActive.value ? '2' : '1';
  sendParams({ [props.sortKey]: value });
};
</script>

<template>
  <button type="button" class="flex items-center justify-center gap-3 mx-auto" @click="changeSort">
    <slot />
    <div :class="twMerge('w-[8px] h-[8px] border-r border-b border-current rotate-45', { '-rotate-[45deg]': isActive })"></div>
  </button>
</template>
