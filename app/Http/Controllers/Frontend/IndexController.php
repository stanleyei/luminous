<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    // 前往前台首頁
    public function index()
    {
        return Inertia::render('Frontend/Index');
    }
}
