/**
 * 將日期字串轉換成中文年月字串
 * @param {string} date 日期字串
 * @returns {string} 中文年月字串
 */
const convertDateToChinese = (date: string): string => {
  const dateFormat = new Date(date);
  if (!date || isNaN(dateFormat.getTime())) return '';
  const year = dateFormat.getFullYear();
  const month = (dateFormat.getMonth() + 1)?.toString()?.padStart(2, '0');
  return `${year}年${month}月`;
};

export { convertDateToChinese };
