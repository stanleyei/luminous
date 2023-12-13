<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\IndexService;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __construct(protected IndexService $indexService)
    {
    }

    // 前往前台首頁
    public function index()
    {
        $rtData = $this->indexService->getIndexData();

        return Inertia::render('Frontend/Index', $rtData);
    }
}
