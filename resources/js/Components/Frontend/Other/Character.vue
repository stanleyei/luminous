<script setup>
import { Application } from '@splinetool/runtime';

const canvas3d = ref(null);

onMounted(() => {
  const app = new Application(canvas3d.value);
  app.load('https://prod.spline.design/RHP7eGnr1mEZkXbA/scene.splinecode');
});
</script>

<template>
  <canvas ref="canvas3d"></canvas>
</template>
