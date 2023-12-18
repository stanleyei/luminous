<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ClientCenterService;

class ClientCenterController extends Controller
{
    public function __construct(protected ClientCenterService $clientCenterService)
    {
    }

    // 前往前台會員中心
    public function index(Request $request)
    {
        $user = $request->user();

        $rtData = $this->clientCenterService->getUserClientData($user);

        return Inertia::render('Frontend/ClientCenter', $rtData);
    }
}
