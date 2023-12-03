<!-- 後台表格列表頁佈局組件 -->

<script setup>
defineProps({
  paginationData: {
    type: Object,
    required: false,
  },
  hasSearch: {
    type: Boolean,
    required: false,
  },
  placeholder: {
    type: String,
    required: false,
    default: '',
  },
});

// 獲取插槽
const slots = useSlots();

// 表格排序、關鍵字請求
const { sendParams, initParamsStore } = useParamsStore();
onMounted(() => {
  initParamsStore();
});
</script>

<template>
  <div class="backend-card-container">
    <!-- 頂部功能列 -->
    <div v-if="!!slots?.top || hasSearch" class="flex justify-between">
      <slot name="top" />

      <!-- 搜尋功能 -->
      <div v-if="hasSearch" class="flex gap-2">
        <input
          v-model="useParamsStore().keywords"
          type="search"
          class="h-[38px] rounded-lg"
          :placeholder="placeholder"
          @search="sendParams()"
          @keyup.enter="sendParams()"
        >
        <button type="button" class="w-6" @click="sendParams()">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 64 64">
            <title>{{ `搜尋${placeholder}` }}</title>
            <path d="M 24 2.8886719 C 12.365714 2.8886719 2.8886719 12.365723 2.8886719 24 C 2.8886719 35.634277 12.365714 45.111328 24 45.111328 C 29.036552 45.111328 33.664698 43.331333 37.298828 40.373047 L 52.130859 58.953125 C 52.130859 58.953125 55.379484 59.435984 57.396484 57.333984 C 59.427484 55.215984 58.951172 52.134766 58.951172 52.134766 L 40.373047 37.298828 C 43.331332 33.664697 45.111328 29.036548 45.111328 24 C 45.111328 12.365723 35.634286 2.8886719 24 2.8886719 z M 24 7.1113281 C 33.352549 7.1113281 40.888672 14.647457 40.888672 24 C 40.888672 33.352543 33.352549 40.888672 24 40.888672 C 14.647451 40.888672 7.1113281 33.352543 7.1113281 24 C 7.1113281 14.647457 14.647451 7.1113281 24 7.1113281 z"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- 表格區塊 -->
    <table class="w-full text-center">
      <thead>
        <tr>
          <slot name="thead" />
        </tr>
      </thead>
      <tbody>
        <slot name="tbody" />
      </tbody>
    </table>

    <!-- 分頁器 -->
    <Pagination v-if="paginationData" :pagination-data="paginationData" />
  </div>
</template>
