<?php

namespace App\Http\Controllers\Backend;

use Inertia\Inertia;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Services\FilesService;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function __construct(protected FilesService $filesService)
    {
    }

    // 前往後台 Banner 管理列表頁
    public function index()
    {
        $bannerData = Banner::select('id', 'photo_path', 'photo_alt')->get();

        $data = [
            'bannerData' => $bannerData,
        ];

        return Inertia::render('Backend/BannerFolder/BannerManage', ['response' => rtFormat($data)]);
    }

    // 前往後台 Banner 管理編輯頁
    public function edit(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:banners,id',
            ]);
        } catch (\Throwable $th) {
            return redirect(route('banner.list'));
        }

        $bannerData = Banner::select('id', 'photo_path', 'photo_alt')->find($request->id);

        $data = [
            'bannerData' => $bannerData,
        ];

        return Inertia::render('Backend/BannerFolder/BannerEdit', ['response' => rtFormat($data)]);
    }

    // 更新 Banner
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:banners,id',
            'photo_path' => 'required|string',
            'photo_alt' => 'required|string|max:255',
        ]);

        $banner = Banner::find($request->id);

        $path = $this->filesService->base64Upload($request->photo_path, 'banner');
        if (!$path) {
            $path = $banner->photo_path;
        } else {
            $this->filesService->deleteUpload($banner->photo_path);
        }

        $banner->update([
            'photo_path' => $path,
            'photo_alt' => $request->photo_alt,
        ]);

        return back()->with(['message' => rtFormat($banner->id)]);
    }
}
