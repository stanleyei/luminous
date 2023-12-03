<!-- 麵包屑組件 -->

<script setup>
const breadcrumb = computed(() => useBreadcrumbStore().breadcrumb);
</script>

<template>
  <section id="breadcrumb">
    <div class="pt-[2px]">
      <svg width="16" height="15" viewBox="0 0 20 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.82369 17.0162V11.5814H12.1715V17.0162C12.1715 17.614 12.6606 18.1031 13.2584 18.1031H16.5193C17.1171 18.1031 17.6062 17.614 17.6062 17.0162V9.40753H19.454C19.954 9.40753 20.1932 8.78797 19.8127 8.46189L10.7258 0.277172C10.3128 -0.0923906 9.68237 -0.0923906 9.26933 0.277172L0.182446 8.46189C-0.187116 8.78797 0.041143 9.40753 0.541139 9.40753H2.38895V17.0162C2.38895 17.614 2.87808 18.1031 3.4759 18.1031H6.73674C7.33456 18.1031 7.82369 17.614 7.82369 17.0162Z" fill="currentColor" />
      </svg>
    </div>
    <div>
      <Link :href="route('dashboard')" title="(跳轉頁面)前往首頁">
        首頁
      </Link>
      <div v-if="breadcrumb.length" class="inline-block pb-px pl-1 pr-2">
        <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1 0.5L6 5.5L1 10.5" stroke="currentColor" stroke-linecap="round" />
        </svg>
      </div>
      <Link :href="title.path" v-for="(title, index) in breadcrumb" :key="index" :class="{ 'pointer-events-none': !title.path }" :title="`(跳轉頁面)${title.name}`">
        <span :key="title.name">
          {{ title.name ?? '' }}
        </span>
        <div v-if="index !== breadcrumb.length - 1" class="inline-block pb-px px-2">
          <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 0.5L6 5.5L1 10.5" stroke="currentColor" stroke-linecap="round" />
          </svg>
        </div>
      </Link>
    </div>
  </section>
</template>

<style scoped>
#breadcrumb {
  @apply flex items-center gap-2 text-lg text-main-black;
}
</style>
