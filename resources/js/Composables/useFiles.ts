type OtherParam = {
  width?: number;
  maxLength?: number;
};

type CtxParam = {
  ctx: CanvasRenderingContext2D;
  width: number;
  height: number;
};

type CanvasImg = CtxParam & {
  canvas: HTMLCanvasElement;
  img: HTMLImageElement;
};

/**
 * 將圖片檔轉成base64並壓縮
 * @param {FileList} files 要轉換的檔案
 * @param {object} option 其餘的設定參數
 * @param {number} option.width 圖片想要輸出的寬度
 * @param {number} option.maxWidth 圖片輸出的最大寬度(預設1024)
 * @returns {Promise<string[]>} 回傳base64的陣列
 */
const fileToBase64 = async (files: FileList, { width, maxLength = 1024 }: OtherParam = {}): Promise<string[]> => {
  const length = files.length;
  if (!length) throw new Error('未傳檔案或檔案類型錯誤');
  const transFile = async () => {
    const arr: string[] = [];
    for (let index = 0; index < length; index++) {
      const file = files[index];
      if (!file.type.includes('image/')) throw new Error('未傳檔案或檔案類型錯誤');
      await new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = async (e) => {
          const { result } = e.target;
          const data = await dealImage(<string>result, width, maxLength);
          resolve(arr.push(data));
        };
      });
    }
    return arr;
  };
  return await transFile();
};

/**
 * 壓縮圖片
 * @param {string} base64 要壓縮的base64圖檔
 * @param {number} wantWidth 圖片想要輸出的寬度
 * @returns {Promise<string>} 回傳壓縮好的base64圖檔
 */
const dealImage = async (base64: string = '', wantWidth: number, maxLength: number): Promise<string> => {
  const quality = 0.8; // 壓縮率
  const { ctx, canvas, img, width, height } = await setBase64ToImg(base64, wantWidth, maxLength);
  ctx.drawImage(img, 0, 0, width, height);

  const imgType = checkAlphaBackground({ ctx, width, height }) ? 'png' : 'jpeg';
  return canvas.toDataURL(`image/${imgType}`, quality);
};

/**
 * 檢查圖片是否為透明背景
 * @param {CtxParam} ctxData 畫布資料
 * @returns {boolean} 回傳是否為透明背景的布林值
 */
const checkAlphaBackground = (ctxData: CtxParam): boolean => {
  const { ctx, width, height } = ctxData;
  const imageData = ctx.getImageData(0, 0, width, height).data;
  const { length } = imageData ?? [];
  let isAlphaBackground = false;
  for (let i = 3; i < length; i += 4) {
    if (imageData[i] != 255) {
      isAlphaBackground = true;
      break;
    }
  }

  return isAlphaBackground;
};

/**
 * 將base64放入img並轉成canvas畫布
 * @param {string} base64 要壓縮的base64圖檔
 * @param {number} wantWidth 圖片想要輸出的寬度
 * @returns {Promise<CanvasImg>} 回傳Canvas相關資訊的物件
 */
const setBase64ToImg = (base64: string = '', wantWidth: number, maxLength: number): Promise<CanvasImg> => {
  const newImage = new Image();
  return new Promise((resolve) => {
    newImage.src = base64;
    newImage.onload = (() => {
      let newWidth = isNaN(wantWidth) ? newImage.width : wantWidth;
      let newHeight = newWidth * newImage.height / newImage.width;
      if (newWidth > maxLength || newHeight > maxLength) {
        const multiple = Math.max(newWidth / maxLength, newHeight / maxLength);
        newWidth = newWidth / multiple;
        newHeight = newHeight / multiple;
      }

      const canvas = document.createElement('canvas');
      canvas.width = newWidth;
      canvas.height = newHeight;
      const ctx = canvas.getContext('2d')

      resolve({ ctx, canvas, img: newImage, width: newWidth, height: newHeight });
    });
  });
};

export { fileToBase64 };
