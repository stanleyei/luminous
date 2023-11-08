type AnyType = number | string | any[] | object;
type isEmptyOption = {
  ignore?: string[];
  min?: number;
};

/**
 * 檢查值是否為物件
 * @param {*} value 要驗證的是否為物件的參數
 * @returns {boolean} 回傳是否為物件的布林值
 */
const isObject = (value: AnyType): boolean => typeof(value) === 'object' && !Array.isArray(value) && value !== null;

/**
 * 檢查是否為正確的Email格式
 * @param {string|string[]} emails 要驗證的Email字串/陣列
 * @returns {boolean} 回傳是否皆為正確的Email格式
 */
const isEmail = (emails: string|string[]): boolean => {
  if (typeof(emails) === 'string') return /^([\w-]+\.*)+@([\w-]+\.)+[\w-]+$/.test(emails);
  if (Array.isArray(emails) && emails.length) return emails.every(email => /^([\w-]+\.*)+@([\w-]+\.)+[\w-]+$/.test(email));
  return false;
};

/**
 * 檢查值是否為最短8位英數字混合，並需包含大小寫英文
 * @param {string} text 要驗證的的字串
 * @returns {boolean} 回傳布林值
 */
const isLetterNumberMixed = (text: string): boolean => {
  return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,}$/.test(text);
};

/**
 * 檢查物件是否為空
 * @param {object} obj 欲檢查的物件
 * @returns {boolean} 回傳物件是否為空的布林值
 */
const objIsEmpty = (obj: AnyType): boolean => {
  if (!isObject(obj)) throw new TypeError('傳入參數型別錯誤');
  return !Object.keys(<object>obj).length;
};

/**
* 檢查值是否為空
* @param {number | string | any[] | object} condition 要驗證的是否為空值的表單或值
* @param {object} option 其他設定項目
* @param {string[]} option.ignore 物件要忽略驗證的key值
* @param {number} option.min 檢查數字型別的下限值(預設為小於1都會認定為空)
* @returns {boolean | string} 回傳是否為空的布林值
*/
const isEmpty = (condition: AnyType, { ignore = [], min = 0 }: isEmptyOption = {}):boolean | string => {
  try {
    if (
      typeof(condition) === 'function' ||
      (ignore && !Array.isArray(ignore)) ||
      (min && typeof(min) !== 'number')
    ) throw new TypeError('傳入參數型別錯誤');

    if (typeof(condition) === 'number') return condition < min;
    if (Array.isArray(condition)) return !condition.length;
    if (isObject(condition)) {
      if (objIsEmpty(condition)) return true;
      let res = false;
      for (const [key, value] of Object.entries(condition)) {
        if (ignore && ignore.includes(key)) continue;
        if (isObject(value)) {
          if (objIsEmpty(value)) return res = true;
        } else {
          if (isEmpty(value, { min })) return res = true;
        }
      }
      return res;
    }

    return (condition ?? '') === '';
  } catch (error) {
    return '傳入參數型別錯誤';
  }
};

/**
 * 生成隨機數字
 * @param {number} length 要生成的隨機數長度
 * @returns {string} 回傳隨機數字字串
 */
const randText = (length: number = 6): string => {
  let code = '';
  const str = '123456789';
  const arr = str.split('');
  let ranNum = null;
  for (let i = 0; i < length; i++) {
    ranNum = Math.floor(Math.random() * 9);
    code += arr[ranNum];
  }

  return code;
};

/**
 * 繪製並產生認證碼
 * @param canvas canvas的dom元素
 * @returns {string} 回傳隨機驗證碼字串
 */
const getVerificationCode = (canvas): string => {
  // 底色
  const baseColor = Object.freeze(['#0000ff', '#CE0000', '#7E3D76', '#46A3FF']);
  // 干擾點顏色
  const sColor = Object.freeze(['#B22222', '#F9F900', '#82D900', '#FFAF60']);
  // 文字顏色
  const fColor = Object.freeze(['#FFA07A', '#00BB00', '#EAC100', '#F75000']);

  const cxt = canvas.value.getContext('2d');
  const colorIndex = Math.floor(Math.random() * baseColor.length);
  cxt.beginPath();
  cxt.fillStyle = baseColor[colorIndex];
  cxt.fillRect(0, 0, 160, 60);

  // 干擾線的隨機x坐標值
  const lineX = () => Math.floor(Math.random() * 160);

  // 干擾線的隨機y坐標值
  const lineY = () => Math.floor(Math.random() * 60);

  // 生成干擾點
  for (let count = 0; count < 60; count++) {
    cxt.beginPath();
    cxt.fillStyle = sColor[colorIndex];
    const arcSize = (Math.floor(Math.random() * (30 - 5 + 1)) + 5) / 10;
    cxt.arc(lineX(), lineY(), arcSize, 0, 2 * Math.PI);
    cxt.fill();
  }

  // 生成干擾線
  for (let count = 0; count < 6; count++) {
    cxt.beginPath();
    cxt.strokeStyle = sColor[colorIndex];
    cxt.moveTo(lineX(), lineY());
    cxt.lineTo(lineX(), lineY());
    cxt.stroke();
  }

  // 生成讓每個字不同Y座標的隨機數字
  const code = randText();
  const textArr = code.split('');
  const textY = () => Math.floor(Math.random() * 20) + 30;
  cxt.fillStyle = fColor[colorIndex];
  cxt.font = 'bold 25px Verdana';
  for (let count = 0; count < textArr.length; count++) {
    const textX = 25 * count + 8;
    cxt.fillText(textArr[count], textX, textY());
  }

  return code;
};

/**
 * 唸出認證碼
 * @param {string} verificationCode 要唸出的認證碼字串
 */
const readVerificationCode = (verificationCode: string) => {
  if (window.speechSynthesis.speaking) return;
  const textArr = verificationCode.split('');
  for (const text of textArr) {
    const msg = new SpeechSynthesisUtterance();
    msg.text = text;
    msg.lang = 'zh-TW'; // 中文語音
    msg.rate = 0.5; // 語速，數值，預設值是1，範圍是0.1到10
    msg.pitch = 1.7; // 表示說話的音高，數值，範圍從0（最小）到2（最大）。預設值為1

    window.speechSynthesis.speak(msg);
  }
};

export { isEmpty, isObject, isEmail, isLetterNumberMixed, getVerificationCode, readVerificationCode };
