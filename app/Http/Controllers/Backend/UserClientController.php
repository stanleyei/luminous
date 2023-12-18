<?php

namespace App\Http\Controllers\Backend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\UserClientService;
use App\Http\Controllers\Controller;

class UserClientController extends Controller
{
    // 初始化注入
    public function __construct(protected UserClientService $userClientService)
    {
    }

    // 前往後台會員管理頁面
    public function index(Request $request)
    {
        $params = (object) [
            'keywords' => $request->q ?? '',
            'sortId' => $request->sortId ?? 0,
            'sortEmail' => $request->sortEmail ?? 0,
            'sortPhone' => $request->sortPhone ?? 0,
        ];

        $rtData = $this->userClientService->getUserClientList($params);

        return Inertia::render('Backend/UserClientFolder/UserClientManage', $rtData);
    }

    // 前往後台會員預覽頁面
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:users,id',
            ]);
        } catch (\Throwable $th) {
            return redirect(route('client.list'));
        }

        $rtData = $this->userClientService->previewUserClient($request->id);

        return Inertia::render('Backend/UserClientFolder/UserClientPreview', $rtData);
    }

    // 切換會員狀態
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $rtData = $this->userClientService->toggleUserStatus($request->id);

        return back()->with($rtData);
    }
}
