<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'submit',
  },
  color: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const colorClass = computed(() => {
  const classList = {
    blue: 'bg-blue-600 hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:ring-blue-500',
    green: 'bg-green-600 hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:ring-green-500',
    red: 'bg-red-600 hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:ring-red-500',
    gray: 'bg-gray-700 hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:ring-gray-600',
  }[props.color] ?? 'bg-gray-800 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500';

  if (props.disabled) {
    return `${classList} opacity-50`;
  }

  return classList;
});
</script>

<template>
  <button
    :type="props.type"
    class="relative inline-flex items-center px-3 py-1 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
    :class="colorClass"
  >
    <slot />
  </button>
</template>
