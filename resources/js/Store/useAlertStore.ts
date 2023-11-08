import { defineStore } from 'pinia';

type AlertData = {
  show: boolean;
  type?: string;
  title?: string;
  content?: string;
  timer?: number;
  loading?: boolean;
  confirm?: Function;
  cancel?: Function;
  confirmContent?: string;
  cancelContent?: string;
};

type InputData = Omit<AlertData, 'show'>;

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alertData: {
      show: false,
      type: 'public',
      title: '',
      content: '',
      timer: 0,
      loading: false,
      confirm: null,
      cancel: null,
      confirmContent: '確定',
      cancelContent: '取消',
    },
  }),
  actions: {
    /**
     * 開啟提醒視窗
     * @param {object} data 傳入提醒視窗的物件
     * @param {string} data.type 提醒視窗的類型(public-預設、success-成功、fail-失敗、warning-警告、tip-提醒、store-新增、update-更新、delete-刪除)
     * @param {string} data.title 提醒視窗的標題
     * @param {string} data.content 提醒視窗的內文
     * @param {number} data.timer 回覆結果視窗的關閉時間
     * @param {boolean} data.loading 顯示等待動畫
     * @param {Function} data.confirm 按下確定後的回調函式
     * @param {Function} data.cancel 按下取消後的回調函式
     * @param {string} data.confirmContent 確定按鈕的文字
     * @param {string} data.cancelContent 取消按鈕的文字
     */
    useAlert(data: InputData) {
      const {
        type = 'public',
        title = '',
        content = '',
        timer = 0,
        loading = false,
        confirm = null,
        cancel = null,
        confirmContent = '確定',
        cancelContent = '取消',
      } = data;

      const { alertData, resetStore, keyupEnter } = this;
      const isResType = ['success', 'fail'].includes(type);
      const resContent = {
        public: content,
        success: '成功',
        fail: '失敗',
        store: '是否要新增?',
        update: '是否要儲存變更?',
        delete: '確定要刪除嗎?',
      }[type] ?? content;

      alertData.type = type;
      alertData.title = title;
      alertData.content = content || resContent;
      alertData.timer = isResType ? 1700 : timer;
      alertData.confirm = confirm;
      alertData.cancel = cancel;
      alertData.loading = loading;
      alertData.confirmContent = confirmContent;
      alertData.cancelContent = cancelContent;
      alertData.show = true;

      if (alertData.timer > 0) return setTimeout(() => resetStore(), alertData.timer);
      setTimeout(() => window.addEventListener('keyup', keyupEnter), 400);
    },

    /**
     * 執行動作
     * @param {string} type 要執行的類型(confirm => 確定；cancel => 取消)
     */
    handleAction(type: 'confirm' | 'cancel') {
      const { alertData, resetStore } = this;
      const callback = alertData[type];
      if (typeof(callback) === 'function') {
        setTimeout(() => alertData.loading = true, 300);
        callback();
      }
      resetStore();
    },

    /**
     * 按下Enter鍵執行確定功能
     * @param {KeyboardEvent} event Enter觸發的事件
     */
    keyupEnter(event: KeyboardEvent) {
      const { handleAction } = this;
      if (event.code === 'Enter' || event.code === 'NumpadEnter') handleAction('confirm');
    },

    // 重置Store
    resetStore() {
      const { alertData, keyupEnter } = this;
      alertData.show = false;
      const delayTime = alertData.loading ? 500 : 300;

      setTimeout(() => {
        this.$reset();
      }, delayTime);

      window.removeEventListener('keyup', keyupEnter);
    },
  },
});
