<?php

namespace App\Http\Controllers\Web\Identity;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product'])->orderByDesc('created_at')->get();
        return view('pages.account.orders', compact('orders'));
    }
}
