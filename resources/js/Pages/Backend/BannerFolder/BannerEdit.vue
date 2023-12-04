<!-- 後台首頁橫幅編輯頁 -->

<script setup>
import { useForm } from '@inertiajs/vue3';
import { sendRequest } from '@/Composables/useRequest';
import { fileToBase64 } from '@/Composables/useFiles';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const bannerData = computed(() => rtData.value.bannerData ?? {});

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '橫幅管理', path: route('banner.list') },
  { name: '編輯橫幅', path: '' },
]);

const form = useForm({
  id: bannerData.value.id ?? 0,
  photo_path: bannerData.value.photo_path ?? '',
  photo_alt: bannerData.value.photo_alt ?? '',
});

// 設定確認彈跳視窗參數
const { useAlert } = useAlertStore();
const alertParam = Object.freeze({
  type: 'update',
  confirm: () => submit(),
});

/**
 * 將圖片資料轉換成base64，並放入form的photo欄位
 * @param {Event} event 上傳圖片的事件
 */
const handlePhoto = async (event) => {
  const { files } = event.target;
  const [img] = await fileToBase64(files, { maxLength: 1260 });
  form.photo_path = img;
};

/**
 * 發送請求
 */
const submit = () => {
  sendRequest(route('banner.update'), 'put', {
    data: form.data(),
    redirect: route('banner.list'),
  });
};
</script>

<template>
  <div class="backend-container">
    <div class="backend-card-container">
      <form @submit.prevent="useAlert(alertParam)" class="space-y-6">
        <!-- 圖片欄位 -->
        <div class="relative">
          <InputLabel for="photo" value="橫幅" />
          <label class="relative block w-full h-[300px] aspect-video mt-1 border border-gray-300 rounded-md shadow-sm cursor-pointer">
            <input type="file" id="photo" class="w-px h-px opacity-0" accept="image/*" @change="handlePhoto">
            <img
              v-if="form.photo_path"
              :src="form.photo_path"
              alt="相片"
              class="absolute inset-0 object-cover w-full h-full rounded-md"
            >
            <div v-else class="absolute inset-0 flex items-center justify-center w-full h-full text-gray-400 bg-gray-100 rounded-md text-2xl">
              <span>尚未上傳圖片</span>
            </div>
          </label>
        </div>

        <!-- 橫幅說明欄位 -->
        <div>
          <InputLabel for="photo-alt" value="橫幅說明" :required="!!form.photo_path" />
          <TextInput
            id="photo-alt"
            v-model="form.photo_alt"
            type="text"
            class="mt-1 block w-full"
            maxlength="255"
            placeholder="請輸入橫幅說明"
            :required="!!form.photo_path"
          />
          <InputError :message="form.errors.photo_alt" class="mt-2" />
        </div>

        <!-- 執行按鈕區塊 -->
        <div class="flex items-center justify-center gap-4">
          <Link :href="route('banner.list')" title="取消新增編輯回到列表頁">
            <PrimaryButton type="button" color="red">取消</PrimaryButton>
          </Link>
          <PrimaryButton type="submit" color="gray" :disabled="form.processing">儲存</PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
