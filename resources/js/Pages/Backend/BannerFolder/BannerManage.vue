<!-- 後台首頁橫幅管理列表頁 -->

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const bannerData = computed(() => rtData.value.bannerData ?? []);

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '橫幅管理', path: '' },
]);

/**
 * 前往編輯頁面
 * @param {number} id 橫幅id
 */
const goToAddEditPage = (id) => {
  router.get(route('banner.edit'), { id });
};
</script>

<template>
  <div class="backend-container">
    <BackendTableList>
      <template #thead>
        <th scope="col" class="backend-table-th">
          橫幅
        </th>
        <th scope="col" class="backend-table-th min-w-[150px] w-[150px]">操作</th>
      </template>

      <template #tbody>
        <tr v-for="banner in bannerData" :key="banner.id">
          <td class="backend-table-td break-all">
            <figure class="w-full h-[300px] aspect-video">
              <img v-if="banner.photo_path" :src="banner.photo_path" :alt="banner.photo_alt" class="object-cover w-full h-[300px] rounded-md">
              <div v-else class="inset-0 flex items-center justify-center w-full h-full text-gray-400 bg-gray-100 rounded-md text-2xl">
                <span>尚未上傳圖片</span>
              </div>
            </figure>
          </td>
          <td class="backend-table-td w-[150px]">
            <div class="flex gap-2 justify-center items-center">
              <PrimaryButton type="button" color="blue" @click="goToAddEditPage(banner.id)">
                編輯
              </PrimaryButton>
            </div>
          </td>
        </tr>
      </template>
    </BackendTableList>
  </div>
</template>
