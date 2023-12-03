<script setup>
defineProps({
  value: {
    type: String,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <label class="block font-medium text-sm text-gray-700 min-h-[24px] leading-6">
    <span v-if="value">{{ value }}</span>
    <span v-else>
      <slot />
    </span>
    <span v-if="required" class="text-red-500 text-base"> *</span>
  </label>
</template>
