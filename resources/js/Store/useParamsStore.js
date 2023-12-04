import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useParamsStore = defineStore('table-params', {
  state: () => ({
    keywords: '',
    sortKey: {},
  }),
  actions: {
    /**
     * 原頁面發送參數
     * @param {object} param 要攜帶的參數
     */
    sendParams(param) {
      if (param) {
        this.sortKey = param;
      }

      const data = {
        ...this.sortKey,
        q: this.keywords,
      };

      const currentRouteName = route()?.current() ?? '';
      router.get(route(currentRouteName), data, {
        preserveState: true,
        preserveScroll: true,
      });
    },

    /**
     * 放入網址參數關鍵字
     * @returns {string} 回傳網址參數關鍵字
     */
    getUrlKeywords() {
      const params = new URL(document.location.href)?.searchParams;
      const keywords = params?.get('q') ?? '';
      return keywords;
    },

    // 初始化參數
    initParamsStore() {
      this.keywords = this.getUrlKeywords();
      this.sortKey = {};
    },
  },
});
