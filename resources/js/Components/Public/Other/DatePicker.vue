<!-- 日期選擇器組件 -->
<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
  initDate: {
    type: [String, Number, Object],
    required: false,
    default: null,
  },
  otherOption: {
    type: Object,
    required: false,
    default: () => ({}),
  },
  noBorder: {
    type: Boolean,
  },
});
const emit = defineEmits(['update']);

const date = computed({
  get: () => props.initDate || null,
  set: (value) => emit('update', value),
});
const baseOption = Object.freeze({
  locale: 'zh-TW',
  format: 'yyyy-MM-dd',
  placeholder: '請選擇時間',
  disabled: false,
  'select-text': '選擇',
  'cancel-text': '取消',
  'enable-time-picker': false,
});

// 日期選擇器其他設定
const dateOption = computed(() => ({ ...baseOption, ...props.otherOption }));
const datepicker = ref(null);
const openMenu = () => {
  datepicker.value.openMenu();
};
</script>

<template>
  <VueDatePicker v-model="date" v-bind="dateOption" ref="datepicker">
    <template #dp-input="{ value }">
      <label tabindex="0" :title="dateOption?.placeholder ?? '請選擇時間'" class="flex items-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-pointer" :class="{ 'px-2 border border-black': !props.noBorder }" @keyup.enter="openMenu">
        <svg v-if="!props.noBorder" width="15" height="15" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.49814 5.41689H2.3321V6.58294H3.49814V5.41689ZM5.83024 5.41689H4.66419V6.58294H5.83024V5.41689ZM8.16233 5.41689H6.99629V6.58294H8.16233V5.41689ZM9.32838 1.33573H8.74536V0.169678H7.57931V1.33573H2.91512V0.169678H1.74907V1.33573H1.16605C0.518891 1.33573 0.00583023 1.86045 0.00583023 2.50177L0 10.6641C0 11.3054 0.518891 11.8302 1.16605 11.8302H9.32838C9.96971 11.8302 10.4944 11.3054 10.4944 10.6641V2.50177C10.4944 1.86045 9.96971 1.33573 9.32838 1.33573ZM9.32838 10.6641H1.16605V4.25084H9.32838V10.6641Z" fill="currentColor" />
        </svg>
        <input type="text" :value="value" class="w-full border-none focus:border-white/0 focus:ring-0 rounded-md py-2 px-3" :class="{ 'cursor-pointer': !dateOption.disabled }" :placeholder="dateOption?.placeholder ?? '請選擇時間'" readonly>
      </label>
    </template>
  </VueDatePicker>
</template>
