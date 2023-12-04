<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class FilesService
{
    /**
     * 防呆：資料夾不存在時將會自動建立資料夾，避免錯誤
     * @param string $dir 要放置的資料夾名稱
     */
    protected function createDir($dir)
    {
        if (!is_dir('upload/')) {
            mkdir('upload/');
        }

        if (!is_dir('upload/' . $dir)) {
            mkdir('upload/' . $dir);
        }
    }

    /**
     * 上傳檔案
     * @param mixed $file 要轉換的File檔案
     * @param string $dir 要放置的資料夾名稱
     * @return object 回傳資料庫儲存的檔名及路徑
     */
    public function fileUpload($file, $dir, $uid = '')
    {
        // 防呆：資料夾不存在時將會自動建立資料夾，避免錯誤
        $this->createDir($dir);

        // 取得檔案的副檔名
        $data = (object) [
            'name' => null,
            'path' => null,
        ];
        $data->name = $file->getClientOriginalName();

        $middleName = $uid ? "-{$uid}-" : '-';

        // 檔案名稱會被重新命名
        $data->path = '/upload/' . $dir . '/' . time() . rand(10, 99) . $middleName . $data->name;
        // 移動到指定路徑
        move_uploaded_file($file, public_path() . $data->path);
        // 回傳 資料庫儲存用的路徑格式

        return $data;
    }

    /**
     * 將base64轉換成本地檔案並上傳
     * @param string $base64 要轉換的base64字串
     * @param string $dir 要放置的資料夾名稱
     * @return string 回傳資料庫儲存用的路徑格式
     */
    public function base64Upload($base64, $dir, $fileType = 'webp')
    {
        // 防呆：base64格式錯誤時回傳空字串
        if (!$base64 || !stripos($base64, ';base64,')) return '';

        // 防呆：資料夾不存在時將會自動建立資料夾，避免錯誤
        $this->createDir($dir);

        list($type, $base64) = explode(';', $base64);
        list(, $extension) = explode('/', $type);
        list(, $base64) = explode(',', $base64);
        $transExtension = $extension !== 'png' ? $fileType : 'png';
        $fileName = strval(time() . md5(rand(100, 200))) . '.' . $transExtension;
        $base64 = base64_decode($base64);
        file_put_contents($fileName, $base64);

        // 移動到指定路徑
        $path = '/upload/' . $dir . '/' . $fileName;
        rename($fileName, public_path() . $path);

        // 回傳 資料庫儲存用的路徑格式
        return $path;
    }

    /**
     * 上傳圖片
     * @param mixed $file 要轉換的File檔案
     * @param string $dir 要放置的資料夾名稱
     * @return string 回傳資料庫儲存用的路徑格式
     */
    public function imgUpload($file, $dir)
    {
        // 防呆：資料夾不存在時將會自動建立資料夾，避免錯誤
        $this->createDir($dir);

        // 取得檔案的副檔名
        $extension = $file->getClientOriginalExtension();
        // 檔案名稱會被重新命名
        $filename = strval(time() . md5(rand(100, 200))) . '.' . $extension;
        // 移動到指定路徑
        $path = '/upload/' . $dir . '/' . $filename;
        move_uploaded_file($file, public_path() . $path);
        // 回傳 資料庫儲存用的路徑格式
        return $path;
    }

    /**
     * 相簿
     * @param mixed $file 要轉換的File檔案
     * @param string $dir 要放置的資料夾名稱
     * @return object 回傳資料庫儲存用的路徑格式
     */
    public function mutiPhotoUpload($file, $dir)
    {
        // 防呆：資料夾不存在時將會自動建立資料夾，避免錯誤
        $this->createDir($dir);

        // 取得檔案的副檔名
        $data = (object) [
            'name' => null,
            'path' => null,
        ];

        $extension = $file->getClientOriginalExtension();
        $data->name = explode('.' . $extension, $file->getClientOriginalName())[0];
        // 檔案名稱會被重新命名
        $filename = strval(time() . md5(rand(100, 200))) . '.' . $extension;
        // 檔案名稱會被重新命名
        $data->path = '/upload/' . $dir . '/' .  $filename;
        // 移動到指定路徑
        move_uploaded_file($file, public_path() . $data->path);
        // 回傳 資料庫儲存用的路徑格式

        return $data;
    }

    /**
     * 複製本地圖片
     * @param string $oldPath 要複製的圖片字串
     * @param string $dir 要放置的資料夾名稱
     * @return string 回傳複製的新圖片路徑
     */
    public function copyUpload($oldPath, $dir)
    {
        // 獲取副檔名
        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $newPath = '/upload/' . $dir . '/' . strval(time() . md5(rand(100, 200))) . '.' . $extension;
        File::copy(public_path($oldPath), public_path($newPath));

        return $newPath;
    }

    /**
     * 找出json字串內的/upload/開頭的圖片路徑並刪除
     * @param string $string 要刪除的字串
     * @return boolean 回傳是否成功刪除
     */
    public function deleteUploadByString($string)
    {
        $pattern = '/\/upload\/[a-zA-Z0-9\/\.\-\_]+/';
        preg_match_all($pattern, $string, $matches);
        foreach ($matches[0] as $match) {
            $this->deleteUpload($match);
        }
        return true;
    }

    /**
     * 刪除上傳檔案
     * @param string $url 要刪的本地檔案路徑(/upload/...)
     * @return boolean 回傳是否成功刪除
     */
    public function deleteUpload($url)
    {
        if (file_exists(public_path() . $url)) {
            File::delete(public_path() . $url);
        }
        return true;
    }
}
