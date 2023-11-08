/**
 * 存放資料到localStorage
 * @param {object} data 要放入的物件資料(key及要存的值)
 */
const setLocal = (data: object) => {
  for (const [key, value] of Object.entries(data)) {
    localStorage.setItem(key, value);
  }
};

/**
 * 拿出localStorage的資料
 * @param {string[]} keys KEY的字串陣列
 * @returns {string[]} 取出對應KEY的資料陣列
 */
const getLocal = (keys: string[]): string[] => keys.map(key => localStorage.getItem(key) ?? '');

/**
 * 移除localStorage指定的資料
 * @param {string[]} keys 要移除資料的KEY的字串陣列
 */
const removeLocal = (keys: string[]) => {
  for (const key of keys) localStorage.removeItem(key);
};

export { setLocal, getLocal, removeLocal };
