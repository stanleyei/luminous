<!-- 後台活動相簿管理新增編輯頁面 -->

<script setup>
import { useForm } from '@inertiajs/vue3';
import { sendRequest, getImgPath } from '@/Composables/useRequest';
import { fileToBase64 } from '@/Composables/useFiles';

const props = defineProps({
  response: Object,
});

const rtData = computed(() => props.response?.rt_data ?? {});
const productData = computed(() => rtData.value.productData ?? {});
const productDataPhotos = computed(() => productData.value.photos ?? []);
const productTypeOption = computed(() => rtData.value.productTypeOption ?? []);

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([
  { name: '商品管理', path: route('product.list') },
  { name: `${productData.value.id ? '編輯' : '新增'}商品`, path: '' },
]);

const form = useForm({
  id: productData.value.id ?? 0,
  type: productData.value.type ?? '',
  title: productData.value.title ?? '',
  title_en: productData.value.title_en ?? '',
  date: productData.value.date ?? '',
  cover_photo_index: productData.value.cover_photo_index ?? 0,
});

// 暫存相片資料
const tempPhoto = reactive(productDataPhotos.value);
// 待刪除的相片id
const deletePhotoIds = ref([]);
// 是否清空檔案
const clearFiles = ref(false);

// 獲取tempPhoto中id的最大值
const photoMaxId = computed(() => {
  if (!tempPhoto.length) return 0;
  const ids = tempPhoto.map((item) => item.id);
  return Math.max(...ids);
});

// tempPhoto中photo_path為base64及檔案路徑的物件
const filterPhotos = computed(() => {
  return tempPhoto.reduce((acc, item) => {
    const key = item.photo_path.includes(';base64,') ? 'base64' : 'file';
    acc[key].push(item);
    return acc;
  }, { base64: [], file: [] });
});

// 設定確認彈跳視窗參數
const { useAlert, resetStore } = useAlertStore();
const alertParam = Object.freeze({
  type: form.id ? 'update' : 'store',
  confirm: () => submit(),
});

// 日期選擇器其他參數
const otherOption = Object.freeze({
  'format': 'yyyy-MM-dd HH:mm',
  'auto-apply': true,
});

/**
 * 將相片放入tempPhoto中
 * @param {File} files 上傳相片的檔案
 */
const setPhotos = async (files) => {
  // 開啟Loading
  useAlert({ loading: true });

  const imgs = await fileToBase64(files, { maxLength: 1181 });
  for (const img of imgs) {
    tempPhoto.push({
      id: photoMaxId.value + 1,
      photo_path: img,
      photo_alt: '',
    });
  }

  // 清空input
  clearFiles.value = true;
  setTimeout(() => clearFiles.value = false, 0);

  // 關閉Loading
  resetStore();
};

/**
 * 刪除指定的相片
 * @param {Number} id 相片id
 */
const deletePhoto = (id) => {
  const index = tempPhoto.findIndex((item) => item.id === id);

  // 如果刪除的相片是封面相片，則將封面相片id設為0，如果刪除的相片在封面相片之前，則將封面相片id減1
  if (index === form.cover_photo_index) {
    form.cover_photo_index = 0;
  } else if (index < form.cover_photo_index) {
    form.cover_photo_index -= 1;
  }

  // 如果刪除的相片是已存在的相片(photo_path不是base64是檔案路徑)，則將相片id放入deletePhotoIds中
  if (!tempPhoto[index].photo_path.includes(';base64,')) {
    deletePhotoIds.value.push(id);
  }

  tempPhoto.splice(index, 1);
};

/**
 * 發送請求
 */
const submit = async () => {
  const data = {
    id: form.id,
    type: form.type,
    title: form.title,
    title_en: form.title_en,
    date: form.date,
    cover_photo_index: form.cover_photo_index,
    photos: [...filterPhotos.value.file],
    delete_photo_ids: deletePhotoIds.value,
  };

  // 如果有base64相片，則將base64上傳並回傳相片路徑
  if (filterPhotos.value.base64.length) {
    setTimeout(() => useAlert({ loading: true }), 500);
    const base64Paths = filterPhotos.value.base64.map((item) => item.photo_path);
    const imgs = await getImgPath(route('product.upload'), 'post', base64Paths);
    const handledImgs = filterPhotos.value.base64.map((item, index) => ({
      id: item.id,
      photo_path: imgs[index],
      photo_alt: item.photo_alt,
    }));
    data.photos = [...filterPhotos.value.file, ...handledImgs];
  }

  const [url, method] = productData.value.id
    ? ['product.update', 'put']
    : ['product.store', 'post'];

  sendRequest(route(url), method, {
    data,
    redirect: route('product.list'),
  });
};
</script>

<template>
  <div class="backend-container">
    <div class="backend-card-container">
      <form @submit.prevent="useAlert(alertParam)" class="flex flex-col justify-between gap-6 min-h-[50dvh]">
        <div class="space-y-6">
          <div class="grid grid-cols-2 gap-6">
            <!-- 相簿名稱欄位 -->
            <div>
              <InputLabel for="title" value="相簿名稱" required />
              <TextInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                maxlength="255"
                placeholder="請輸入相簿名稱"
                required
              />
              <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <!-- 相簿英文名稱欄位 -->
            <div>
              <InputLabel for="title-en" value="相簿英文名稱" />
              <TextInput
                id="title-en"
                v-model="form.title_en"
                type="text"
                class="mt-1 block w-full"
                maxlength="255"
                placeholder="請輸入相簿英文名稱"
              />
              <InputError :message="form.errors.title_en" class="mt-2" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <!-- 類型欄位 -->
            <div>
              <InputLabel for="type" value="類型" required />
              <SelectOption
                id="type"
                v-model="form.type"
                class="mt-1 block w-full"
                required
              >
                <option value="" hidden>請選擇類型</option>
                <option v-for="typeOption in productTypeOption" :key="typeOption.id" :value="typeOption.id">
                  {{ typeOption.zh }}
                </option>
              </SelectOption>
              <InputError :message="form.errors.type" class="mt-2" />
            </div>

            <!-- 日期欄位 -->
            <div class="relative">
              <InputLabel for="date" value="日期" required />
              <DatePicker
                :init-date="form.date"
                :other-option="otherOption"
                class="mt-1"
                @update="(date) => form.date = date"
              />
              <input v-model="form.date" type="text" class="absolute z-[-1] bottom-0 left-1/2 w-px h-px opacity-0" required>
              <InputError :message="form.errors.date" class="mt-2" />
            </div>
          </div>

          <!-- 活動照片欄位 -->
          <div class="relative">
            <InputLabel for="photo-cover" value="活動照片(建議比例為 16 : 9，建議尺寸為 1181 * 663 px)" required />
            <FileInput
              for-id="photo-cover"
              class="mt-1"
              accept="image"
              multiple
              :need-clear="clearFiles"
              @update="setPhotos"
            />
            <input v-model="tempPhoto" type="text" class="absolute z-[-1] bottom-0 w-px h-px opacity-0" required>

            <!-- 活動照片 -->
            <div v-if="tempPhoto.length" class="grid grid-cols-3 gap-3 mt-3">
              <figure v-for="(photo, index) in tempPhoto" :key="photo.id" class="relative">
                <!-- 設為封面選項 -->
                <div class="flex items-center gap-2">
                  <input v-model="form.cover_photo_index" :id="`cover-check-${photo.id}`" :key="index" type="checkbox" class="rounded-md cursor-pointer" :true-value="index" :false-value="0">
                  <InputLabel :for="`cover-check-${photo.id}`" value="設為封面" class="cursor-pointer" />
                </div>

                <!-- 照片 -->
                <img
                  :src="photo.photo_path"
                  alt="活動照片"
                  class="h-[270px] object-cover rounded-md mt-1"
                />

                <!-- 輸入照片名稱/標題 -->
                <TextInput
                  v-model="photo.photo_alt"
                  type="text"
                  class="mt-1 block w-full"
                  maxlength="255"
                  required
                  placeholder="請輸入照片名稱/說明"
                />

                <!-- 刪除照片按鈕 -->
                <button type="button" class="absolute top-[25px] right-px border-[2px] border-transparent rounded-full hover:border-red-500" @click="deletePhoto(photo.id)">
                  <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="36" height="36" viewBox="0 0 48 48">
                    <title>刪除照片</title>
                    <path fill="#f44336" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path fill="#fff" d="M29.656,15.516l2.828,2.828l-14.14,14.14l-2.828-2.828L29.656,15.516z"></path><path fill="#fff" d="M32.484,29.656l-2.828,2.828l-14.14-14.14l2.828-2.828L32.484,29.656z"></path>
                  </svg>
                </button>
              </figure>
            </div>
          </div>
        </div>

        <!-- 執行按鈕區塊 -->
        <div class="flex items-center justify-center gap-4">
          <Link :href="route('product.list')" title="取消新增編輯回到列表頁">
            <PrimaryButton type="button" color="red">取消</PrimaryButton>
          </Link>
          <PrimaryButton type="submit" color="gray" :disabled="form.processing">儲存</PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>