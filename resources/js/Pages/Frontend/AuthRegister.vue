<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <div class="flex justify-center items-center md:min-h-[calc(100dvh-229px)] min-h-[calc(100dvh-201px)] px-4">
    <section class="w-96 border-4 border-main-swamp-green/80 text-main-swamp-green">
      <RegisterLoginNav />
      <form class="py-8 px-4" @submit.prevent="submit">
        <label>
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            placeholder="請輸入Email"
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </label>

        <label class="block mt-4">
          <TextInput
            id="phone"
            type="tel"
            class="mt-1 block w-full"
            v-model="form.phone"
            placeholder="請輸入手機號碼"
            minlength="10"
            maxlength="10"
            pattern="^09\d{8}$"
            required
          />

          <InputError class="mt-2" :message="form.errors.name" />
        </label>

        <label class="block mt-4">
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            placeholder="請輸入密碼(最少8碼)"
            minlength="8"
            maxlength="16"
            required
            autocomplete="new-password"
          />

          <InputError class="mt-2" :message="form.errors.password" />
        </label>

        <label class="block mt-4">
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            placeholder="請再次輸入密碼(最少8碼)"
            minlength="8"
            maxlength="16"
            required
            autocomplete="new-password"
          />

          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </label>

        <div class="mt-14">
          <div class="flex justify-center">
            <PrimaryButton
              class="py-2 !px-20 !bg-main-yellow !text-main-swamp-green"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              註冊
            </PrimaryButton>
          </div>
        </div>
      </form>
    </section>
  </div>
</template>
