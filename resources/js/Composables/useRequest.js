import { usePage, router } from '@inertiajs/vue3';
import { useAlertStore } from '@/Store/useAlertStore';
import { isObject } from './useFormVerification';

/**
 * 確認請求的方法是否正確
 * @param {string} method 請求的方法
 * @return {boolean} 回傳請求的方法是否正確
 */
const isCorrectMethod = (method) => ['post', 'put', 'delete'].includes(method);

/**
 * 發送請求
 * @param {string} url 請求路徑
 * @param {string} method 請求方法(post、put、delete)
 * @param {object} option 其餘設定
 * @param {object} option.data 請求要攜帶的資料
 * @param {string} option.redirect 請求成功時想要前往的路徑
 * @param {boolean} option.reload 請求成功時是否重新加載當頁(預設為true)
 * @param {boolean} option.response 請求後是否要跳出提示視窗(預設為true)
 * @param {string} option.successContent 請求成功時想要自定義的彈跳視窗內容(選填)
 * @param {string} option.failContent 請求失敗時想要自定義的彈跳視窗內容(選填)
 * @param {object} option.customAlert 請求成功時想要自定義的彈跳視窗(選填)
 * @returns {Promise<object>} 回傳請求後回應的訊息
 */
const sendRequest = (url, method, { data, redirect = '', reload = true, response = true, successContent = '', failContent = '', customAlert = {} } = {}) => {
  const isNotString = [url, redirect, successContent, failContent].some(item => typeof(item) !== 'string');
  if (isNotString || !isCorrectMethod(method) || !isObject(customAlert)) throw new TypeError('參數未傳或型別錯誤');
  const { useAlert } = useAlertStore();
  return new Promise((resolve) => {
    router.visit(url, {
      method,
      data,
      preserveState: true,
      preserveScroll: true,
      onSuccess: ({ props: { flash: { message = {} } = {} } = {} } = {}) => {
        resolve(message);
        const res = message?.rt_code ? 'success' : 'fail';
        if (response) {
          if (Object.keys(customAlert).length && res === 'success') {
            useAlert(customAlert);
          } else {
            const failMessage = message?.rt_message ?? failContent;
            useAlert({
              type: res,
              content: res === 'success' ? successContent : failMessage,
            });
          }
        }
        if (res === 'fail' || !reload) return;
        const currentUrl = usePage().url;
        setTimeout(() => router.get(redirect || currentUrl), 1500);
      },
      onError: (error) => {
        const [[, value]] = Object.entries(error);
        useAlert({
          type: 'warning',
          content: value,
          cancelContent: '',
        });
      },
    });
  });
};

/**
 * 發送base64返回圖片路徑
 * @param {string} url 請求路徑
 * @param {string} method 請求方法(post、put、delete)
 * @param {string[]} data 請求要攜帶的base64陣列
 * @returns {[]} 回傳圖片路徑陣列
 */
const getImgPath = async (url, method, data) => {
  if (typeof(url) !== 'string' || !isCorrectMethod(method) || !Array.isArray(data)) throw new Error('參數未傳或型別錯誤');
  const { length } = data;
  const sentImg = async () => {
    const arr = [];
    for (let index = 0; index < length; index++) {
      await new Promise((resolve) => {
        router.visit(url, {
          method,
          data: { img: data[index] },
          preserveState: true,
          preserveScroll: true,
          onSuccess: ({ props: { flash: { message } = {} } = {} }) => {
            resolve(arr.push(message?.rt_data ?? ''));
          },
        });
      });
    }
    return arr;
  };
  return await sentImg();
};

export { sendRequest, getImgPath };
