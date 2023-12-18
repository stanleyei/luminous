<!-- 競標彈窗組件 -->

<script setup>
import { sendRequest } from '@/Composables/useRequest';

const props = defineProps({
  show: Boolean,
  productData: Object,
});

const emit = defineEmits(['close']);
const bidPrice = ref(0);

const submit = () => {
  const { useAlert } = useAlertStore();
  if (!bidPrice.value) {
    useAlert({
      type: 'fail',
      content: '請輸入金額',
    });
    return;
  }

  if (bidPrice.value < props.productData.price) {
    useAlert({
      type: 'fail',
      content: '出價金額不得低於目前價格',
    });
    return;
  }

  sendRequest(route('product.bid'), 'post', {
    data: { id: props.productData.id, price: bidPrice.value },
  });
};

// 當打開彈窗時，將html滾軸設定為不可滾動，且滾軸升至最頂
watch(
  () => props.show,
  (val) => {
    if (val) {
      window.scrollTo({ top: 0 });
      document.documentElement.style.overflow = 'hidden';
    } else {
      document.documentElement.style.overflow = 'auto';
    }
  },
  { immediate: true },
);
</script>

<template>
  <ModalDialog :show-modal="props.show" class="min-w-[330px] rounded-lg shadow-md">
    <form class="flex flex-col items-center gap-6 py-8 px-4 bg-white" @submit.prevent="submit">
      <div class="flex items-end">
        <p>競標價格：</p>
        <p class="text-main-light-red font-bold text-2xl">NT$ {{ props.productData.price }}</p>
      </div>

      <label for="bid-price" class="w-[250px]">
        <input
          id="bid-price"
          v-model.number="bidPrice"
          type="number"
          class="w-full rounded-lg tracking-widest focus:ring-main-swamp-green/80 focus:border-main-swamp-green/80"
          min="1"
          max="99999999"
          placeholder="請輸入金額"
          required
        >
      </label>

      <!-- 按鈕組 -->
      <div class="flex gap-4">
        <button type="button" class="px-10 rounded-lg border border-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 transition-colors hover:bg-gray-100" @click="emit('close')">
          關閉
        </button>
        <button type="submit" class="px-10 rounded-lg bg-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 transition-colors hover:bg-[#CCCAB1]">
          送出
        </button>
      </div>
    </form>
  </ModalDialog>
</template>
