<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Domains\Catalog\Entities\Product;
use App\Domains\Identity\Entities\User;
use App\Domains\Order\Entities\Order;

class DashboardController
{
    public function index()
    {
        // dữ liệu demo, backend có thể cung cấp số liệu thật sau
        $totalProducts = Product::count();
        $newOrders = Order::where('status', 'pending')->count();
        $customers = User::count();
        $recentOrders = Order::latest()->limit(8)->get();

        return view('admin.dashboard', compact('totalProducts','newOrders','customers','recentOrders'));
    }
}
