<!-- 後台修改密碼頁 -->

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { isEmpty } from '@/Composables/useFormVerification';
import { sendRequest } from '@/Composables/useRequest';

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

// 設置麵包屑
const { setBreadcrumb } = useBreadcrumbStore();
setBreadcrumb([{ name: '修改密碼', path: '' }]);

// 設定確認彈跳視窗參數
const { useAlert } = useAlertStore();
const alertParam = Object.freeze({
  content: '確定要修改密碼嗎？',
  confirm: () => submit(),
});

/**
 * 發送請求
 */
const submit = () => {
  sendRequest(route('password.update'), 'put', {
    data: form.data(),
    customAlert: {
      content: '修改成功，請重新登入',
      cancelContent: '',
      confirm: () => router.post(route('logout')),
    },
  });
};
</script>

<template>
  <div class="backend-container">
    <div class="backend-card-container">
      <form @submit.prevent="!isEmpty(form.data()) && useAlert(alertParam)" class="space-y-6">
        <!-- 舊密碼欄位 -->
        <div>
          <InputLabel for="current-password" value="舊密碼" required />
          <TextInput
            id="current-password"
            v-model="form.current_password"
            type="password"
            class="mt-1 block w-full"
            minlength="8"
            maxlength="255"
            placeholder="請輸入舊密碼"
            required
          />
          <InputError :message="form.errors.current_password" class="mt-2" />
        </div>

        <!-- 新密碼欄位 -->
        <div>
          <InputLabel for="password" value="新密碼(最少8碼)" required />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            minlength="8"
            maxlength="255"
            placeholder="請輸入新密碼"
            required
          />
          <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <!-- 再次確認密碼欄位 -->
        <div>
          <InputLabel for="password_confirmation" value="再次確認密碼(最少8碼)" required />
          <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="mt-1 block w-full"
            minlength="8"
            maxlength="255"
            placeholder="請再次輸入密碼"
            required
          />
          <InputError :message="form.errors.password_confirmation" class="mt-2" />
        </div>

        <!-- 執行按鈕區塊 -->
        <div class="flex items-center justify-center gap-4">
          <PrimaryButton type="submit" :disabled="form.processing">儲存</PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
