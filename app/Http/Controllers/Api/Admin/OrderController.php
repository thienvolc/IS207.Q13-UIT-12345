<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends ApiController
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

