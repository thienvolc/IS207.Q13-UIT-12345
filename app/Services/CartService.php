<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Constants\CartStatus;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get or create cart for current user
     */
    public function getOrCreateCart(): array
    {
        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with(['items.product'])
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'status' => CartStatus::ACTIVE,
            ]);
            $cart->load(['items.product']);
        }

        return CartResource::transform($cart);
    }

    /**
     * Add item to cart
     */
    public function addItem(array $data): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $data) {
            // Get or create cart
            $cart = Cart::firstOrCreate(
                ['user_id' => $userId, 'status' => CartStatus::ACTIVE],
                ['user_id' => $userId, 'status' => CartStatus::ACTIVE]
            );

            // Validate product with pessimistic locking to prevent race conditions
            $product = Product::where('product_id', $data['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$product) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Product not found'
                ]);
            }

            if ($product->status !== ProductStatus::ACTIVE) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Product is not available'
                ]);
            }

            // Check if item already exists in cart
            $cartItem = CartItem::where('cart_id', $cart->cart_id)
                ->where('product_id', $data['product_id'])
                ->first();

            $newQuantity = $cartItem ? ($cartItem->quantity + $data['quantity']) : $data['quantity'];

            // Validate stock with the total quantity needed
            if ($product->quantity < $newQuantity) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Not enough product in stock',
                    'available' => $product->quantity,
                    'in_cart' => $cartItem ? $cartItem->quantity : 0,
                    'requested' => $data['quantity'],
                    'total_needed' => $newQuantity,
                ]);
            }

            if ($cartItem) {
                // Update existing cart item
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price, // Update price to current price
                    'discount' => $product->discount ?? 0, // Update discount to current discount
                    'note' => $data['note'] ?? $cartItem->note,
                ]);
            } else {
                // Create new cart item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'quantity' => $data['quantity'],
                    'note' => $data['note'] ?? null,
                ]);
            }

            $cartItem->load('product');

            return [
                'item_id' => $cartItem->cart_item_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
            ];
        });
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(int $cartItemId, array $data): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $cartItemId, $data) {
            $cartItem = CartItem::whereHas('cart', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', CartStatus::ACTIVE);
            })->find($cartItemId);

            if (!$cartItem) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Cart item not found'
                ]);
            }

            // Validate product exists and is active WITH PESSIMISTIC LOCKING
            $product = Product::where('product_id', $cartItem->product_id)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Product not found'
                ]);
            }

            if ($product->status !== ProductStatus::ACTIVE) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Product is not available'
                ]);
            }

            // Validate product stock
            if ($product->quantity < $data['quantity']) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Not enough product in stock',
                    'available' => $product->quantity,
                    'requested' => $data['quantity'],
                ]);
            }

            // Update price and discount to current product values
            $cartItem->update([
                'quantity' => $data['quantity'],
                'price' => $product->price,
                'discount' => $product->discount ?? 0,
            ]);
            $cartItem->load('product');

            return CartItemResource::transform($cartItem);
        });
    }

    /**
     * Delete cart item
     */
    public function deleteItem(int $cartItemId): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = CartItem::whereHas('cart', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', CartStatus::ACTIVE);
            })->find($cartItemId);

            if (!$cartItem) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Cart item not found'
                ]);
            }

            $deletedItem = $cartItem->replicate();
            $deletedItem->load('product');

            $cartItem->delete();

            return CartItemResource::transform($deletedItem);
        });
    }

    /**
     * Clear all items in cart
     */
    public function clearCart(): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId) {
            $cart = Cart::where('user_id', $userId)
                ->where('status', CartStatus::ACTIVE)
                ->first();

            if (!$cart) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Cart not found'
                ]);
            }

            // Delete all items
            CartItem::where('cart_id', $cart->cart_id)->delete();

            $cart->load('items');

            return CartResource::transform($cart);
        });
    }

    /**
     * Checkout cart - save shipping info
     */
    public function checkout(array $data): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $data) {
            $cart = Cart::where('user_id', $userId)
                ->where('status', CartStatus::ACTIVE)
                ->with('items.product')
                ->first();

            if (!$cart) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Cart not found'
                ]);
            }

            // Validate items exist and are in user's cart
            $requestedItems = $data['items'];
            $cartItems = CartItem::whereIn('cart_item_id', $requestedItems)
                ->where('cart_id', $cart->cart_id)
                ->with('product')
                ->get();

            if ($cartItems->count() !== count($requestedItems)) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Some items are not in your cart'
                ]);
            }

            // Validate all products are still available
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Product {$item->product_id} not found"
                    ]);
                }

                if ($item->product->status !== ProductStatus::ACTIVE) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Product {$item->product->title} is not available"
                    ]);
                }

                if ($item->product->quantity < $item->quantity) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Not enough stock for {$item->product->title}"
                    ]);
                }
            }

            // Save checkout info to cart
            $cart->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'line1' => $data['line1'],
                'line2' => $data['line2'] ?? null,
                'city' => $data['city'],
                'province' => $data['province'],
                'country' => $data['country'],
                'note' => $data['note'] ?? null,
                'status' => CartStatus::CHECKED_OUT,
            ]);

            return [
                'cart_id' => $cart->cart_id,
                'item_count' => $cartItems->count(),
                'shipping_address' => [
                    'line1' => $cart->line1,
                    'line2' => $cart->line2,
                    'city' => $cart->city,
                    'province' => $cart->province,
                    'country' => $cart->country,
                ],
                'items' => CartItemResource::collection($cartItems),
            ];
        });
    }
}
