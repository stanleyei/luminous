<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { getLocal } from '@/Composables/useStorage';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
});

const flashMessage = computed(() => usePage().props?.flash?.message);

/**
 * 獲取localStorage中的資料並設置至表單
 */
const setLocalStorage = () => {
  const [email = '', password = ''] = getLocal(['luminous-email', 'luminous-password']);
  const remember = !!email && !!password;
  form.email = email;
  form.password = password;
  form.remember = remember;
};

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

/**
 * 當記住密碼被勾選且送出時，將密碼儲存至 localStorage
 */
const rememberPassword = () => {
  const { remember, email, password } = form;
  const handler = remember ? 'setItem' : 'removeItem';
  localStorage[handler]('luminous-email', email);
  localStorage[handler]('luminous-password', password);
};

/**
 * 登入
 */
const submit = () => {
  rememberPassword();
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};

onMounted(() => {
  setLocalStorage();
});

const { useAlert } = useAlertStore();
watch(
  () => flashMessage.value,
  () => {
    if (flashMessage.value?.rt_code === 0) {
      useAlert({
        type: 'fail',
        content: flashMessage.value?.rt_message,
      });
    }
  },
  { immediate: true },
);
</script>

<template>
  <div class="flex justify-center items-center md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] px-4">
    <section class="w-96 border-4 border-main-swamp-green/80 text-main-swamp-green">
      <RegisterLoginNav />
      <form class="py-8 px-4" @submit.prevent="submit">
        <label>
          <TextInput
            v-model="form.email"
            id="email"
            type="email"
            class="mt-1 block w-full"
            placeholder="請輸入電子信箱"
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </label>

        <label class="block mt-4">
          <TextInput
            v-model="form.password"
            id="password"
            type="password"
            class="mt-1 block w-full"
            placeholder="請輸入密碼"
            autocomplete="current-password"
            required
          />

          <InputError class="mt-2" :message="form.errors.password" />
        </label>

        <div class="block mt-4">
          <label class="flex items-center cursor-pointer">
            <Checkbox v-model:checked="form.remember" class="cursor-pointer text-main-swamp-green/80" />
            <span class="ml-2 text-sm">記住我</span>
          </label>
        </div>

        <div class="mt-14">
          <div class="flex justify-center">
            <PrimaryButton
              class="py-2 !px-20 !bg-main-yellow !text-main-swamp-green"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              登入
            </PrimaryButton>
          </div>

          <div class="flex justify-center pt-2">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="font-bold underline text-sm rounded-md"
            >
              忘記密碼?
            </Link>
          </div>
        </div>
      </form>
    </section>
  </div>
</template>
