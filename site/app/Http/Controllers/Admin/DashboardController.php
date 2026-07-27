<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdInquiry;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'activeAds' => Ad::where('is_active', true)->count(),
            'totalAds' => Ad::count(),
            'unreadInquiries' => AdInquiry::where('is_read', false)->count(),
            'totalInquiries' => AdInquiry::count(),
        ]);
    }
}
