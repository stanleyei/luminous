<script setup>
const loadingDelay = ref(true);
const loadingBall = ref([
  { i: 1, p: 1 },
  { i: 0.8, p: 0.8 },
  { i: 0.6, p: 0.6 },
  { i: 0.4, p: 0.4 },
  { i: 0.2, p: 0.2 },
]);

setTimeout(() => loadingDelay.value = false, 1000);
</script>

<template>
  <div id="loaging" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-black">
    <div
      v-for="(ball, key) in loadingBall"
      :key="key"
      class="round"
      :class="{ 'opacity-0': loadingDelay }"
      :style="`--i: ${ball.i}; --p: ${ball.p}`"
    >
      <div class="ball" :class="{ 'first-ball': key === 0 }"></div>
    </div>
  </div>
</template>

<style scoped>
.round {
  @apply absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[100px] h-[100px] origin-[50%];
  transition: opacity 0.5s ease-in-out;
  animation: round 2s infinite cubic-bezier(.3, .4, .7, var(--p));
}

.ball {
  @apply rounded-[50%] bg-current;
  width: calc(25px * var(--i));
  height: calc(25px * var(--i));
  transform: translate(43px, calc(-20px * var(--i)));
}

.first-ball {
  animation: scale 2s infinite;
  transform: translate(38px, calc(-20px * var(--i)));
}

@keyframes scale {
  0% {
    width: 25px;
    height: 25px;
  }

  50% {
    width: calc(25px * .8);
    height: calc(25px * .8);
  }

  80% {
    width: 25px;
    height: 25px;
  }
}

@keyframes round {
  65% {
    transform: translate(-50%, -50%) rotate(1turn);
  }

  100% {
    transform: translate(-50%, -50%) rotate(1turn);
  }
}
</style>
