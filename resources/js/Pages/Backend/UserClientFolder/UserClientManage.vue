<!-- 後台會員管理頁 -->

<script setup>
import { router } from '@inertiajs/vue3';
import { sendRequest } from '@/Composables/useRequest';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const paginationData = computed(() => rtData.value.userClientData ?? {});

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '會員管理', path: '' },
]);

// 設定確認彈跳視窗參數
const { useAlert } = useAlertStore();

/**
 * 導航到特定客戶的預覽頁面
 * @param {number} id - 客戶的ID
 */
const goToPrviewPage = (id) => {
  router.get(route('client.preview'), { id });
};

/**
 * 切換使用者客戶端的狀態
 * @param {number} id - 使用者客戶端的ID
 */
const switchStatus = (id) => {
  useAlert({
    type: 'update',
    confirm: () => sendRequest(route('client.status', { id }), 'put'),
  });
};
</script>

<template>
  <div class="backend-container">
    <BackendTableList :pagination-data="paginationData" placeholder="會員名稱" has-search has-pagination>
      <template #thead>
        <th scope="col" class="backend-table-th min-w-[80px] w-[80px]">
          <BackendThSortBtn sort-key="sortId">
            序號
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[250px] w-[250px]">
          <BackendThSortBtn sort-key="sortEmail">
            Email
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[150px] w-[150px]">
          <BackendThSortBtn sort-key="sortPhone">
            會員電話
          </BackendThSortBtn>
        </th>
        <th scope="col" class="backend-table-th min-w-[150px] w-[150px]">操作</th>
      </template>

      <template #tbody>
        <tr v-for="client in paginationData?.data ?? []" :key="client.id">
          <td class="backend-table-td break-all w-[80px]">
            {{ client.id }}
          </td>
          <td class="backend-table-td break-all min-w-[250px] w-[250px]">
            {{ client.email }}
          </td>
          <td class="backend-table-td break-all min-w-[150px] w-[150px]">
            {{ client.phone }}
          </td>
          <td class="backend-table-td w-[150px]">
            <div class="flex gap-2 justify-center items-center">
              <PrimaryButton type="button" color="blue" @click="goToPrviewPage(client.id)">
                檢視
              </PrimaryButton>
              <PrimaryButton type="button" :color="client.status ? 'red' : 'green'" @click="switchStatus(client.id)">
                {{ client.status ? '停權' : '啟用' }}
              </PrimaryButton>
            </div>
          </td>
        </tr>
      </template>
    </BackendTableList>
  </div>
</template>
