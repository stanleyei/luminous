<!-- 後台商品管理列表頁 -->

<script setup>
import { router } from '@inertiajs/vue3';
import { sendRequest } from '@/Composables/useRequest';
import { twMerge } from 'tailwind-merge';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const paginationData = computed(() => rtData.value.productData ?? {});
const productTypeOption = computed(() => rtData.value.productTypeOption ?? []);
const currentType = computed(() => rtData.value.currentType ?? 1);
const stateSwitch = ref(false);
const featuredSwitch = ref(false);

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
const changeStatus = async (id) => {
  stateSwitch.value = true;
  await sendRequest(route('product.status', { id }), 'put', {
    reload: false,
    response: false,
  });

  stateSwitch.value = false;
};

/**
 * 設置推薦商品
 * @param {number} id 商品 id
 */
const changeFeatured = async (id) => {
  featuredSwitch.value = true;
  await sendRequest(route('product.featured', { id }), 'put', {
    reload: false,
    response: false,
  });

  featuredSwitch.value = false;
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
    <BackendTableList :pagination-data="paginationData" placeholder="商品名稱" has-search has-pagination>
      <template #top>
        <div class="flex gap-3">
          <PrimaryButton type="button" color="green" @click="goToAddEditPage(0)">
            新增
          </PrimaryButton>
        </div>
      </template>

      <template #table-top>
        <ul class="flex">
          <li
            v-for="typeOption in productTypeOption"
            :key="typeOption.id"
            :class="twMerge('border border-main-swamp-green/80 border-b-0 rounded-t-md transition-colors hover:bg-gray-300', currentType === typeOption.id && 'bg-main-swamp-green/80 text-white hover:bg-main-swamp-green/80')"
          >
            <Link :href="route('product.list', { type: typeOption.id })" class="block py-2 px-3">
              {{ typeOption.name }}
            </Link>
          </li>
        </ul>
      </template>

      <template #thead>
        <th scope="col" class="backend-table-th min-w-[300px] w-[300px]">
          封面照片
        </th>
        <th scope="col" class="backend-table-th min-w-[200px]">
          <BackendThSortBtn sort-key="sortName">
            商品名稱
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[180px] w-[180px]">
          <BackendThSortBtn sort-key="sortStartTime">
            開始時間
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[120px] w-[120px]">
          <BackendThSortBtn sort-key="sortPrice">
            商品價格
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[230px] w-[230px]">操作</th>
      </template>

      <template #tbody>
        <tr v-for="product in paginationData?.data ?? []" :key="product.id">
          <td class="backend-table-td break-all w-[300px]">
            <figure class="w-full aspect-video">
              <img v-if="product.cover_photo_path" :src="product.cover_photo_path" :alt="product.cover_photo_alt" class="h-[200px] object-cover rounded-md" width="356">
              <div v-else class="inset-0 flex items-center justify-center w-full h-[200px] text-gray-400 bg-gray-100 rounded-md text-2xl">
                <span>尚未上傳圖片</span>
              </div>
            </figure>
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
            <div>
              <div class="flex gap-2 justify-center items-center mb-4">
                <!-- 推薦 -->
                <PrimaryButton type="button" class="px-0" @click="changeFeatured(product.id)">
                  <label :for="`featured-check-${product.id}`" class="flex items-center gap-2 w-full cursor-pointer" :class="{ 'cursor-default': featuredSwitch }">
                    <input :id="`featured-check-${product.id}`" type="checkbox" class="rounded-md cursor-pointer text-gray-800" :checked="!!product.featured" :disabled="featuredSwitch">
                    推薦
                  </label>
                </PrimaryButton>

                <!-- 上下架 -->
                <PrimaryButton type="button" class="px-0 bg-main-light-gray" @click="changeStatus(product.id)">
                  <label :for="`status-check-${product.id}`" class="flex items-center gap-2 w-full cursor-pointer" :class="{ 'cursor-default': stateSwitch }">
                    <input :id="`status-check-${product.id}`" type="checkbox" class="rounded-md cursor-pointer text-main-light-gray" :checked="!!product.status" :disabled="stateSwitch">
                    上下架
                  </label>
                </PrimaryButton>
              </div>
              <div class="flex gap-2 justify-center items-center">
                <PrimaryButton type="button" color="blue" @click="goToAddEditPage(product.id)">
                  編輯
                </PrimaryButton>
                <PrimaryButton type="button" color="red" @click="deleteProduct(product.id)">
                  刪除
                </PrimaryButton>
              </div>
            </div>
          </td>
        </tr>
      </template>
    </BackendTableList>
  </div>
</template>
