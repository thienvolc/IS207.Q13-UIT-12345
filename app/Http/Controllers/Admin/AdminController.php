<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
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
