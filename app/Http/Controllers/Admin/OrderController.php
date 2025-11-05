<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppController;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends AppController
{
    public function status(Request $request, $id)
    {
        $order = Order::find($id);
        if (! $order) {
            return $this->error('Order not found', '404002', 404);
        }

        return $this->success(['id' => $order->id, 'status' => $order->status]);
    }

    public function confirm(Request $request, $id)
    {
        $order = Order::find($id);
        if (! $order) {
            return $this->error('Order not found', '404002', 404);
        }

        $order->status = 'processing';
        $order->save();

        return $this->success(['id' => $order->id, 'status' => $order->status], 'Order confirmed');
    }
}
