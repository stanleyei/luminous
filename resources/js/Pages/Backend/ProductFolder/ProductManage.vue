<!-- 後台商品管理列表頁 -->

<script setup>
import { router } from '@inertiajs/vue3';
import { sendRequest } from '@/Composables/useRequest';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const paginationData = computed(() => rtData.value.newsData ?? {});
const topSwitch = ref(false);

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '商品管理', path: '' },
]);

// 設定確認彈跳視窗參數
const { useAlert } = useAlertStore();

/**
 * 前往新增/編輯頁面
 * @param {number} id 商品 id
 */
const goToAddEditPage = (id) => {
  router.get(route('product.edit'), { id });
};

/**
 * 設置上下架
 * @param {number} id 商品 id
 */
const setTop = async (id) => {
  topSwitch.value = true;
  await sendRequest(route('product.status', { id }), 'put', {
    reload: false,
    response: false,
  });

  topSwitch.value = false;
};

/**
 * 刪除商品
 * @param {number} id 商品 id
 */
const deleteProduct = (id) => {
  useAlert({
    type: 'delete',
    confirm: () => sendRequest(route('product.destroy', { id }), 'delete'),
  });
};
</script>

<template>
  <div class="backend-container">
    <BackendTableList :pagination-data="paginationData" placeholder="商品名稱" has-search>
      <template #top>
        <div class="flex gap-3">
          <PrimaryButton type="button" color="green" @click="goToAddEditPage(0)">
            新增
          </PrimaryButton>
        </div>
      </template>

      <template #thead>
        <th scope="col" class="backend-table-th w-[370px]">
          封面照片
        </th>
        <th scope="col" class="backend-table-th w-[120px]">
          <BackendThSortBtn sort-key="sortName">
            商品名稱
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th w-[120px]">
          <BackendThSortBtn sort-key="sortStartTime">
            開始時間
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th">
          <BackendThSortBtn sort-key="sortPrice">
            商品價格
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th w-[230px]">操作</th>
      </template>

      <template #tbody>
        <tr v-for="product in paginationData?.data ?? []" :key="product.id">
          <td class="backend-table-td break-all w-[370px]">
            <img :src="product.cover_photo_path" :alt="eventAlbum.cover_photo_alt" width="356" class="h-[200px]">
          </td>
          <td class="backend-table-td break-all w-[120px]">
            {{ product.name }}
          </td>
          <td class="backend-table-td break-all w-[120px]">
            {{ product.start_time }}
          </td>
          <td class="backend-table-td break-all w-[200px]">
            {{ product.price }}
          </td>
          <td class="backend-table-td w-[230px]">
            <div class="flex gap-2 justify-center items-center">
              <PrimaryButton type="button" class="px-0 bg-main-light-gray" :disabled="topSwitch" @click="setTop(product.id)">
                <label :for="`top-check-${product.id}`" class="flex items-center gap-1 w-full cursor-pointer" :class="{ 'cursor-default': topSwitch }">
                  <input :id="`top-check-${product.id}`" type="checkbox" class="rounded-md cursor-pointer text-main-light-gray" :checked="product.top" :disabled="topSwitch">
                  置頂
                </label>
              </PrimaryButton>
              <PrimaryButton type="button" color="blue" @click="goToAddEditPage(product.id)">
                編輯
              </PrimaryButton>
              <PrimaryButton type="button" color="red" @click="deleteProduct(product.id)">
                刪除
              </PrimaryButton>
            </div>
          </td>
        </tr>
      </template>
    </BackendTableList>
  </div>
</template>
