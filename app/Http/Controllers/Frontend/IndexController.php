<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\IndexService;
use App\Http\Controllers\Controller;
use App\Services\ProductListService;

class IndexController extends Controller
{
    public function __construct(
        protected IndexService $indexService,
        protected ProductListService $productListService
    ) {
    }

    // 前往前台首頁
    public function index()
    {
        $this->productListService->winningBid();
        $rtData = $this->indexService->getIndexData();

        return Inertia::render('Frontend/Index', $rtData);
    }
}
