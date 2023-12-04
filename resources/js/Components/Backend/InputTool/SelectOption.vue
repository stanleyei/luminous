<!-- 下拉選單組件 -->

<script setup>
defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const select = ref(null);

onMounted(() => {
  if (select.value.hasAttribute('autofocus')) {
    select.value.focus();
  }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
  <select
    ref="select"
    :value="modelValue"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-pointer"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <slot />
  </select>
</template>
