<!-- 公用互動彈跳視窗組件 -->

<script setup>
const props = defineProps({
  showModal: {
    type: Boolean,
  },
});

const modalDialog = ref(null);
const fadeEffect = ref(false);

/**
 * 關閉彈跳視窗
 */
const closeModal = () => {
  fadeEffect.value = false;
  setTimeout(() => modalDialog.value.close(), 300);
};

// 監聽 showModal 變化然後開關彈跳視窗
watch(
  () => props.showModal,
  (value) => {
    if (!value) {
      closeModal();
      return;
    }

    fadeEffect.value = true;
    modalDialog.value.showModal();
  },
);
</script>

<template>
  <dialog ref="modalDialog" class="modal-dialog" :class="{ open: fadeEffect }">
    <slot />
  </dialog>
</template>

<style scoped>
:global(dialog) {
  @apply p-0;
}

.modal-dialog, .modal-dialog::backdrop {
  @apply relative transition-opacity duration-300 opacity-0;
}

.modal-dialog {
  @apply bg-transparent;
}

.modal-dialog::backdrop {
  @apply bg-black/50;
}

.open, .open::backdrop {
  @apply opacity-100;
}
</style>
