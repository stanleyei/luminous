<!-- 後台會員資料預覽頁 -->

<script setup>
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const userClientData = computed(() => rtData.value.userClientData ?? {});
const selectedTab = ref(1);
const productData = computed(() => {
  const data = userClientData.value.products ?? [];
  return data.filter(item => item.pivot_status === selectedTab.value);
});

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '會員管理', path: route('client.list') },
  { name: '會員檢視', path: '' },
]);
</script>

<template>
  <div class="backend-container">
    <div class="backend-card-container">
      <section>
        <h2 class="font-bold text-lg pb-6">{{ userClientData.email }} 競標資訊</h2>

        <!-- 頁籤 -->
        <nav class="flex">
          <button
            type="button"
            :class="twMerge('tab-btn', selectedTab === 1 && 'selected-tab')"
            @click="selectedTab = 1"
          >
            競標中
          </button>
          <button
            type="button"
            :class="twMerge('tab-btn', selectedTab === 2 && 'selected-tab')"
            @click="selectedTab = 2"
          >
            已得標
          </button>
        </nav>

        <!-- 競標商品 -->
        <div class="overflow-y-auto">
          <table class="w-full text-center">
            <thead>
              <tr>
                <th scope="col" class="backend-table-th min-w-[180px] w-[180px]">
                  封面照片
                </th>
                <th scope="col" class="backend-table-th min-w-[100px] w-[100px]">
                  商品類別
                </th>
                <th scope="col" class="backend-table-th min-w-[200px]">
                  商品名稱
                </th>
                <th scope="col" class="backend-table-th min-w-[120px] w-[120px]">
                  出價金額
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in productData" :key="product.id">
                <td class="backend-table-td break-all w-[180px]">
                  <figure class="w-full aspect-video">
                    <img v-if="product.cover_photo_path" :src="product.cover_photo_path" alt="商品圖片" class="h-[200px] object-cover rounded-md" width="356">
                    <div v-else class="inset-0 flex items-center justify-center w-full h-[200px] text-gray-400 bg-gray-100 rounded-md text-2xl">
                      <span>尚未上傳圖片</span>
                    </div>
                  </figure>
                </td>
                <td class="backend-table-td break-all w-[100px]">
                  {{ product.type }}
                </td>
                <td class="backend-table-td break-all w-[200px]">
                  {{ product.name }}
                </td>
                <td class="backend-table-td break-all w-[120px]">
                  {{ product.bid_price }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
.tab-btn {
  @apply md:w-auto w-1/2 py-2 md:px-20 rounded-t-lg border-x border-t border-main-swamp-green/80;
}

.selected-tab {
  @apply border-transparent bg-main-swamp-green/80 text-white cursor-default;
}
</style>
