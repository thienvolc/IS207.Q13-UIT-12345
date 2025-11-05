<?php

namespace App\Http\Controllers\Api\Me;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function place(Request $request)
    {
        $userId = Auth::id() ?: $request->attributes->get('auth_user_id');
        if (! $userId) {
            return $this->error('Unauthorized', '401000', 401);
        }

        $cart = Cart::where('user_id', $userId)->with('items.product')->first();
        if (! $cart || $cart->items->isEmpty()) {
            return $this->error('Cart is empty', '400010', 400);
        }

        $promo = $request->input('promo');
        $note = $request->input('note');

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $discountTotal = 0;

            foreach ($cart->items as $ci) {
                $subtotal += $ci->price * $ci->quantity;
                $discountTotal += $ci->discount * $ci->quantity;
            }

            $tax = 0; // simplify
            $shipping = 0; // simplify
            $total = $subtotal;
            $grand = $total - $discountTotal + $tax + $shipping;

            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'discount_total' => $discountTotal,
                'promo' => $promo,
                'grand_total' => $grand,
                'status' => 'pending',
                'note' => $note,
            ]);

            foreach ($cart->items as $ci) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                    'price' => $ci->price,
                    'discount' => $ci->discount,
                    'note' => $ci->note,
                ]);

                // reduce stock
                $product = $ci->product;
                if ($product) {
                    $product->quantity = max(0, $product->quantity - $ci->quantity);
                    $product->save();
                }
            }

            // clear cart
            $cart->items()->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error('Failed to create order', '500002', 500);
        }

        return $this->success([
            'id' => $order->id,
            'status' => $order->status,
            'grand_total' => (string) $order->grand_total
        ], 'Order created');
    }

    public function status(Request $request, $id)
    {
        $userId = Auth::id() ?: $request->attributes->get('auth_user_id');
        if (! $userId) {
            return $this->error('Unauthorized', '401000', 401);
        }

        $order = Order::find($id);
        if (! $order) {
            return $this->error('Order not found', '404002', 404);
        }

        if ($order->user_id !== $userId) {
            return $this->error('Forbidden', '403002', 403);
        }

        return $this->success(['id' => $order->id, 'status' => $order->status]);
    }
}

