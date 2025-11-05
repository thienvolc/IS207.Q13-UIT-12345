<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\ApiController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends ApiController
{
    public function index(Request $request)
    {
        $userId = Auth::id() ?: $request->attributes->get('auth_user_id');
        if (! $userId) {
            return $this->error('Unauthorized', '401000', 401);
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $items = $cart->items()->with('product')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => (string) $item->price,
                'discount' => (string) $item->discount,
                'note' => $item->note,
                'product' => $item->product ? [
                    'title' => $item->product->title,
                    'slug' => $item->product->slug,
                    'thumb' => $item->product->thumb
                ] : null
            ];
        });

        return $this->success([
            'id' => $cart->id,
            'total_count' => $items->count(),
            'items' => $items,
        ]);
    }

    public function addItem(Request $request)
    {
        $userId = Auth::id() ?: $request->attributes->get('auth_user_id');
        if (! $userId) {
            return $this->error('Unauthorized', '401000', 401);
        }

        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        if (! $productId || $quantity <= 0) {
            return $this->error('Invalid product_id or quantity', '400001', 400);
        }

        $product = Product::find($productId);
        if (! $product) {
            return $this->error('Product not found', '404001', 404);
        }

        // simple stock check
        if ($product->quantity < $quantity) {
            return $this->error('Insufficient stock', '400002', 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // add or increment item
        $item = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

        DB::beginTransaction();
        try {
            if ($item) {
                $item->quantity += $quantity;
                $item->price = $product->price;
                $item->discount = $product->discount;
                $item->save();
            } else {
                $item = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'discount' => $product->discount,
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error('Failed to add item', '500001', 500);
        }

        return $this->success([
            'id' => $item->id,
            'cart_id' => $cart->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => (string) $item->price,
            'discount' => (string) $item->discount,
            'note' => $item->note,
        ]);
    }
}

