<!-- 提醒的彈跳視窗 -->

<script setup>
const alertData = computed(() => useAlertStore().alertData);
const { handleAction } = useAlertStore();
</script>

<template>
  <ModalDialog :show-modal="alertData.show" class="min-w-[330px] rounded-lg shadow-md">
    <Loading v-show="alertData.loading" class="text-main-blue" />
    <section v-show="!alertData.loading" class="border border-gray-300 rounded-md">
      <div class="flex flex-col justify-center items-center gap-4 w-full h-[calc(100%-42px)] p-[30px] bg-white rounded-md" :class="{ 'h-full': alertData.timer }">
        <div class="flex flex-col justify-center items-center gap-5 h-auto text-2xl font-bold break-all">
          <svg v-show="alertData.type === 'fail' || alertData.type === 'warning'" width="100" height="100" viewBox="0 0 188 188" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title>警告圖示</title>
            <circle cx="94" cy="94" r="92.7411" stroke="#CB0000" strokeWidth="2.51786" />
            <path d="M101.312 50.7245C108.175 64.0527 99.7069 115.2 99.7069 115.2H88.7579C88.7579 115.2 79.1738 65.5905 87.2101 50.7245C89.8798 45.7859 98.741 45.7312 101.312 50.7245ZM85.8916 134.83C85.8916 132.479 86.7133 130.52 88.3566 128.953C89.9999 127.346 92.0063 126.543 94.3757 126.543C96.7069 126.543 98.6942 127.346 100.337 128.953C101.981 130.52 102.802 132.479 102.802 134.83C102.802 137.103 101.981 139.043 100.337 140.649C98.7324 142.216 96.8407 143 94.6623 143C92.1018 143 89.9999 142.197 88.3566 140.59C86.7133 138.984 85.8916 137.064 85.8916 134.83Z" fill="#CB0000" />
          </svg>
          <svg v-show="alertData.type === 'success' || alertData.type === 'tip'" width="100" height="100" viewBox="0 0 188 188" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title>成功圖示</title>
            <path d="M70.5056 122.795L44.4785 96.7675C41.5784 93.8674 36.9679 93.8674 34.0677 96.7675C31.1675 99.6677 31.1675 104.278 34.0677 107.178L65.2259 138.337C68.126 141.237 72.8109 141.237 75.7111 138.337L154.536 59.586C157.436 56.6858 157.436 52.0753 154.536 49.1751C151.636 46.275 147.025 46.275 144.125 49.1751L70.5056 122.795Z" fill="#5B9A45" />
            <circle cx="94" cy="94" r="92.7411" stroke="#5B9A45" stroke-width="2.51786" />
          </svg>
          <div v-show="alertData.title" class="text-4xl text-center" v-html="alertData.title"></div>
          <div v-html="alertData.content"></div>
        </div>
        <div v-if="!alertData.timer" class="w-full">
          <div class="flex justify-center gap-[15px] w-full">
            <button v-if="alertData.cancelContent" type="button" class="handle-btn" @click="handleAction('cancel')">
              {{ alertData.cancelContent }}
            </button>
            <button v-if="alertData.confirmContent" type="button" class="handle-btn bg-main-blue text-white" @click="handleAction('confirm')">
              {{ alertData.confirmContent }}
            </button>
          </div>
        </div>
      </div>
    </section>
  </ModalDialog>
</template>

<style scoped>
.handle-btn {
  @apply py-2 px-[7%] rounded-md border border-black text-xl;
}
</style>
