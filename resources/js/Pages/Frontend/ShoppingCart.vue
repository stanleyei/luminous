<!-- 前台購物車頁 -->

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import { sendRequest } from '@/Composables/useRequest';

const user = computed(() => usePage().props.auth.user ?? {});
const successfulBidProducts = computed(() => usePage().props.successfulBidProducts ?? []);
const selectedBidProducts = ref(successfulBidProducts.value.map((bidProducts) => bidProducts.id));
const totalPrice = computed(() => {
  return successfulBidProducts.value.reduce((total, bidProducts) => {
    if (!selectedBidProducts.value.includes(bidProducts.id)) return total;
    return total + bidProducts.bid_price;
  }, 0);
});

const form = useForm({
  name: '',
  phone: user.value?.user_client?.phone ?? '',
  address: '',
});

const { useAlert } = useAlertStore();
const showTipAlert = () => {
  if (!selectedBidProducts.value.length) {
    useAlert({
      type: 'fail',
      content: '請選擇商品',
    });
    return;
  }

  useAlert({
    content: '是否確定結帳？',
    confirm: () => sendRequest(route('shoppingCart.pay'), 'post', {
      data: { ids: selectedBidProducts.value },
      redirect: route('home'),
    }),
  });
};
</script>

<template>
  <form class="flex flex-col gap-y-4 md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] py-7 px-4" @submit.prevent="showTipAlert">
    <!-- 訂單詳情區塊 -->
    <section>
      <h2 class="mb-4 py-1 bg-main-yellow font-bold text-xl text-center text-main-swamp-green/80">訂單詳情</h2>
      <div v-if="successfulBidProducts.length" class="flex flex-col items-center gap-y-3">
        <figure
          v-for="bidProducts in successfulBidProducts"
          :key="bidProducts.id"
          class="flex items-center justify-between w-full py-3 md:pl-7 pl-2 md:pr-7 pr-2 rounded bg-gray-100/50 text-center break-keep"
        >
          <label class="relative flex items-center p-3 md:mr-7 mr-2 rounded-full cursor-pointer">
            <input
              v-model="selectedBidProducts"
              type="checkbox"
              :value="bidProducts.id"
              class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none text-main-brown rounded-md border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-main-brown checked:bg-main-brown checked:before:bg-main-brown hover:before:opacity-10 focus:ring-0"
            >
          </label>
          <div class="flex md:gap-x-5 gap-x-3 flex-1 mr-2">
            <img :src="bidProducts.cover_photo_path" alt="商品圖片" width="72" class="h-[72px] object-cover rounded-md">
            <div class="flex flex-col justify-between md:text-base text-sm">
              <p class="flex md:items-center text-start break-all">{{ bidProducts.name }}</p>
              <p class="text-start text-red-500 font-bold">NT$ {{ bidProducts.bid_price.toLocaleString() }}</p>
            </div>
          </div>
          <button type="button" class="p-2 rounded-lg bg-red-600 text-white md:text-base text-sm hover:bg-red-700 transition-colors">放棄商品</button>
        </figure>
      </div>
      <div v-else class="flex justify-center">
        <Link :href="route('product.index')" title="前往競標(跳轉頁面)" class="inline-block py-2 px-8 rounded-lg bg-[#CCCAB1]/70 text-lg font-bold text-main-swamp-green/80 text-center transition-colors hover:bg-[#CCCAB1]">
          尚無得標商品，前往競標
        </Link>
      </div>
    </section>

    <section class="flex flex-col gap-y-4">
      <h3 class="py-1 pl-4 bg-main-yellow font-bold text-lg text-main-swamp-green/80">寄送資訊</h3>
      <div class="pl-4">
        <InputLabel for="name" value="聯絡人" required />
        <TextInput
          v-model="form.name"
          id="name"
          type="text"
          class="mt-1 block w-full tracking-wider"
          maxlength="10"
          placeholder="請輸入聯絡人"
          required
        />
      </div>
      <div class="pl-4">
        <InputLabel for="phone" value="聯絡手機" required />
        <TextInput
          v-model="form.phone"
          id="phone"
          type="tel"
          class="mt-1 block w-full tracking-wider"
          minlength="10"
          maxlength="10"
          pattern="^09\d{8}$"
          placeholder="請輸入聯絡手機"
          required
        />
      </div>
      <div class="pl-4">
        <InputLabel for="address" value="聯絡地址" required />
        <TextInput
          v-model="form.address"
          id="address"
          type="text"
          class="mt-1 block w-full tracking-wider"
          maxlength="255"
          placeholder="請輸入聯絡地址"
          required
        />
      </div>
    </section>

    <!-- 訂單金額合計 -->
    <section class="flex flex-col gap-y-4">
      <h3 class="py-1 pl-4 bg-main-yellow font-bold text-lg text-main-swamp-green/80">訂單合計</h3>
      <div class="pr-4 text-end text-2xl text-red-500 font-bold underline underline-offset-4">
        NT$ {{ totalPrice.toLocaleString() }}
      </div>
    </section>

    <section class="mx-auto pt-6">
      <button type="submit" class="py-2 px-8 rounded-lg bg-main-yellow font-bold text-lg text-main-swamp-green/80 transition-colors hover:bg-yellow-400 hover:text-main-swamp-green">
        結帳
      </button>
    </section>
  </form>
</template>
