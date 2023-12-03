<!-- 後台頁首組件 -->

<script setup>
import { usePage } from '@inertiajs/vue3';
import { menuLinks } from '@/Composables/useBackendRoute';
import logoLuminous from '/images/logo/logo-luminous.png';

const showingNavigationDropdown = ref(false);
const user = computed(() => usePage().props.auth.user);
</script>

<template>
  <header id="backend-header">
    <div class="flex justify-between items-center h-[65px] px-[28px]">
      <section class="flex items-center">
        <Link href="/" title="前往前台首頁(跳轉頁面)">
          <h1>
            <img :src="logoLuminous" alt="Luminous Logo" width="150" class="w-[150px]">
          </h1>
        </Link>
      </section>

      <section class="relative flex items-center gap-6">
        <!-- 選單 -->
        <BackendDropdown class="hidden md:flex">
          <template #trigger>
            <span class="inline-flex rounded-md">
              <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 hover:text-white focus:outline-none transition ease-in-out duration-150">
                {{ user.name }}

                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </span>
          </template>

          <template #content>
            <Link :href="route('logout')" method="post" as="button" class="dropdown-link">
              登出
            </Link>
            <Link :href="route('password.edit')" title="前往修改密碼" class="dropdown-link">
              修改密碼
            </Link>
          </template>
        </BackendDropdown>

        <!-- 手機版漢堡圖示 -->
        <div class="-mr-2 flex items-center md:hidden">
          <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = !showingNavigationDropdown">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path
                :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </section>
    </div>

    <!-- 手機版選單 -->
    <div class="fixed top-[65px] left-0 md:hidden w-full bg-main-dark-green" :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }">
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
          <div class="font-medium text-base text-white">
            {{ user.name }}
          </div>
          <div class="font-medium text-sm text-gray-400">{{ user.email }}</div>
        </div>

        <div class="h-[280px] pt-2 pb-3 border-b border-white space-y-1 overflow-auto">
          <ResponsiveNavLink
            v-for="list in menuLinks"
            :key="list.id"
            :href="route(list.path)"
            :title="`前往${list.name}(跳轉頁面)`"
            @click="showingNavigationDropdown = false"
          >
            {{ list.name }}
          </ResponsiveNavLink>
        </div>

        <div class="mt-3 space-y-1">
          <ResponsiveNavLink :href="route('logout')" method="post" as="button">
            登出
          </ResponsiveNavLink>
          <ResponsiveNavLink :href="route('password.edit')" title="前往修改密碼" @click="showingNavigationDropdown = false">
            修改密碼
          </ResponsiveNavLink>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
#backend-header {
  @apply relative z-[1] w-full bg-main-dark-green text-white;
  box-shadow: 0 4px 4px rgba(#000, .25);
}

.dropdown-link {
  @apply block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out;
}
</style>
