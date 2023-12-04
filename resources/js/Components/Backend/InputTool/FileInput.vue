<!-- 上傳檔案組件 -->

<script setup>
const props = defineProps({
  forId: {
    type: String,
    required: true,
    default: '',
  },
  needClear: {
    type: Boolean,
    required: false,
    default: false,
  },
  required: {
    type: Boolean,
    required: false,
    default: false,
  },
  multiple: {
    type: Boolean,
    required: false,
    default: false,
  },
  accept: {
    type: String,
    required: false,
    default: 'file',
  },
});

const emit = defineEmits(['update']);
const input = ref(null);

/**
 * 判斷接受的檔案類型
 */
const acceptType = computed(() => {
  const type = {
    file: '.csv,.pdf,.odt,.ods,.odp,.jpg,.png',
    image: 'image/*',
  };

  return type[props.accept] ?? '';
});

/**
 * 每次上傳檔案後清空input
 * @param {Event} event 上傳事件
 */
const updateClearInput = (event) => {
  emit('update', event.target.files);
  if (!props.needClear) return;
  setTimeout(() => input.value.value = '', 1000);
};

// 監聽needClear來清空input事件
watch(() => props.needClear, (value) => {
  if (!value) return;
  input.value.value = '';
});
</script>

<template>
  <label :for="props.forId" class="relative inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
    {{ props.accept === 'file' ? '選擇檔案' : '上傳圖片' }}
    <input
      :id="props.forId"
      ref="input"
      type="file"
      class="absolute w-px h-px opacity-0"
      :accept="acceptType"
      :multiple="props.multiple"
      :required="props.required"
      @change="updateClearInput"
    >
  </label>
</template>
