<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <div class="flex flex-col justify-center items-center md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] px-4">
    <section class="md:w-96 w-full">
      <div class="mb-4 md:text-base text-sm text-gray-600">
        忘記密碼了嗎？ 請輸入您註冊的電子郵件地址，我們就會透過電子郵件向您發送密碼重設信。
      </div>

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>

      <form @submit.prevent="submit" class="w-full">
        <div>
          <InputLabel for="email" value="Email" required />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            送出重設密碼信
          </PrimaryButton>
        </div>
      </form>
    </section>
  </div>
</template>
