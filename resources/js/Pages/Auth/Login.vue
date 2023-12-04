<script setup>
import { useForm } from '@inertiajs/vue3';
import { getLocal } from '@/Composables/useStorage';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

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
</script>

<template>
  <div id="backend-login">
    <Head title="登入" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" required />

        <TextInput
          v-model="form.email"
          id="email"
          type="email"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="密碼" required />

        <TextInput
          v-model="form.password"
          id="password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="current-password"
          required
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="block mt-4">
        <label class="flex items-center cursor-pointer">
          <Checkbox v-model:checked="form.remember" class="cursor-pointer" />
          <span class="ml-2 text-sm text-gray-600">記住我</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          忘記密碼?
        </Link>

        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          登入
        </PrimaryButton>
      </div>
    </form>
  </div>
</template>
