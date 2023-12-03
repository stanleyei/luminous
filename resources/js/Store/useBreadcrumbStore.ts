import { defineStore } from 'pinia';

export const useBreadcrumbStore = defineStore('breadcrumb', {
  state: () => ({
    breadcrumb: [],
  }),
  actions: {
    /**
     * 設置麵包屑內容
     * @param {object[]} content 要設置的陣列
     */
    setBreadcrumb(content: object[]) {
      this.breadcrumb = content;
    },

    /**
     * 清空麵包屑內容
     */
    clearBreadcrumb() {
      this.$reset();
    }
  },
});
