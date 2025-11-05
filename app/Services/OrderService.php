<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Constants\OrderStatus;
use App\Constants\CartStatus;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShortOrderResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * Get user orders with filter
     */
    public function getUserOrders(?int $status, int $offset = 0, int $limit = 10, string $sortField = 'orders_at', string $sortOrder = 'desc'): array
    {
        $userId = Auth::id();

        $query = Order::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        $totalCount = $query->count();

        $orders = $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => ShortOrderResource::collection($orders),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    /**
     * Place order from cart
     */
    public function placeOrder(array $data): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $data) {
            // Get cart
            $cart = Cart::where('cart_id', $data['cart_id'])
                ->where('user_id', $userId)
                ->where('status', CartStatus::CHECKED_OUT)
                ->with('items.product')
                ->first();

            if (!$cart) {
                throw new BusinessException(ResponseCode::NOT_FOUND, [], [
                    'message' => 'Cart not found or not checked out'
                ]);
            }

            if ($cart->items->isEmpty()) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Cart is empty'
                ]);
            }

            // Validate all items still available WITH PESSIMISTIC LOCKING to prevent race conditions
            $productIds = $cart->items->pluck('product_id')->toArray();
            $products = Product::whereIn('product_id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('product_id');

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);

                if (!$product || $product->status !== ProductStatus::ACTIVE) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Product {$item->product_id} is not available"
                    ]);
                }

                if ($product->quantity < $item->quantity) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Not enough stock for product {$product->title}",
                        'available' => $product->quantity,
                        'requested' => $item->quantity,
                    ]);
                }
            }

            // Calculate totals
            $subtotal = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            $discountTotal = $cart->items->sum(function($item) {
                return $item->discount * $item->quantity;
            });

            $tax = $subtotal * 0.1; // 10% tax
            $shipping = 30000; // Fixed shipping
            $total = $subtotal + $tax + $shipping;

            // Apply promo discount
            $promoDiscount = 0;
            if (!empty($data['promo'])) {
                $promoDiscount = $total * 0.05; // 5% discount for demo
            }

            $grandTotal = $total - $discountTotal - $promoDiscount;

            // Validate grand total is not negative
            if ($grandTotal < 0) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Invalid order total calculation'
                ]);
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'discount_total' => $discountTotal,
                'promo' => $data['promo'] ?? null,
                'discount' => $promoDiscount,
                'grand_total' => $grandTotal,
                'first_name' => $cart->first_name,
                'middle_name' => $cart->middle_name,
                'last_name' => $cart->last_name,
                'phone' => $cart->phone,
                'email' => $cart->email,
                'line1' => $cart->line1,
                'line2' => $cart->line2,
                'city' => $cart->city,
                'province' => $cart->province,
                'country' => $cart->country,
                'note' => $cart->note,
                'status' => OrderStatus::PENDING_PAYMENT,
                'orders_at' => now(),
            ]);

            // Create order items and reduce product quantity atomically
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $cartItem->product_id,
                    'price' => $cartItem->price,
                    'discount' => $cartItem->discount,
                    'quantity' => $cartItem->quantity,
                    'note' => $cartItem->note,
                ]);

                // Reduce product quantity atomically to prevent negative inventory
                $product = $products->get($cartItem->product_id);
                $updated = Product::where('product_id', $product->product_id)
                    ->where('quantity', '>=', $cartItem->quantity)
                    ->decrement('quantity', $cartItem->quantity);

                if (!$updated) {
                    throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                        'message' => "Failed to reserve stock for {$product->title}. Product may have been purchased by another user."
                    ]);
                }
            }

            // Mark cart as completed
            $cart->update(['status' => CartStatus::COMPLETED]);

            // Delete cart items
            CartItem::where('cart_id', $cart->cart_id)->delete();

            return ShortOrderResource::transform($order);
        });
    }

    /**
     * Get order details
     */
    public function getOrderDetails(int $orderId): array
    {
        $userId = Auth::id();

        $order = Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->with('items')
            ->first();

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return OrderResource::transform($order);
    }

    /**
     * Get order status
     */
    public function getOrderStatus(int $orderId): array
    {
        $userId = Auth::id();

        $order = Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return [
            'order_id' => $order->order_id,
            'status' => $order->status,
        ];
    }

    /**
     * Update order shipping information
     */
    public function updateShipping(int $orderId, array $data): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $orderId, $data) {
            $order = Order::where('order_id', $orderId)
                ->where('user_id', $userId)
                ->first();

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            // Only allow update if order is pending or paid
            if (!in_array($order->status, [OrderStatus::PENDING_PAYMENT, OrderStatus::PAID])) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Cannot update shipping for this order status'
                ]);
            }

            $order->update($data);
            $order->load('items');

            return OrderResource::transform($order);
        });
    }

    /**
     * Cancel order
     */
    public function cancelOrder(int $orderId): array
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId, $orderId) {
            $order = Order::where('order_id', $orderId)
                ->where('user_id', $userId)
                ->lockForUpdate() // Lock order to prevent concurrent cancellations
                ->first();

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            // Check if already cancelled to prevent duplicate inventory restoration
            if ($order->status === OrderStatus::CANCELLED) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Order is already cancelled'
                ]);
            }

            // Only allow cancel if order is pending, paid, or processing
            if (!in_array($order->status, [OrderStatus::PENDING_PAYMENT, OrderStatus::PAID, OrderStatus::PROCESSING])) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Cannot cancel this order. Order status does not allow cancellation.',
                    'current_status' => $order->status,
                ]);
            }

            // Load items with products
            $order->load('items.product');

            // Restore product quantities atomically
            foreach ($order->items as $item) {
                if ($item->product) {
                    Product::where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            $order->update(['status' => OrderStatus::CANCELLED]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    /**
     * Search orders for admin
     */
    public function searchOrdersAdmin(array $filters, int $page = 1, int $size = 10, string $sortField = 'orders_at', string $sortOrder = 'desc'): array
    {
        $query = Order::query()->with('items');

        // Apply filters
        if (!empty($filters['query'])) {
            $query->where(function($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['query'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['query'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['query'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['query'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['start'])) {
            $query->whereDate('orders_at', '>=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $query->whereDate('orders_at', '<=', $filters['end']);
        }

        if (isset($filters['min'])) {
            $query->where('grand_total', '>=', $filters['min']);
        }

        if (isset($filters['max'])) {
            $query->where('grand_total', '<=', $filters['max']);
        }

        $totalCount = $query->count();
        $totalPage = (int)ceil($totalCount / $size);

        $orders = $query->orderBy($sortField, $sortOrder)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get();

        return [
            'data' => OrderResource::collection($orders),
            'current_page' => $page,
            'total_page' => $totalPage,
            'total_count' => $totalCount,
            'has_more' => $page < $totalPage,
        ];
    }

    /**
     * Get order details for admin
     */
    public function getOrderDetailsAdmin(int $orderId): array
    {
        $order = Order::with('items')->find($orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return OrderResource::transform($order);
    }

    /**
     * Update order status (admin)
     */
    public function updateOrderStatus(int $orderId, int $status): array
    {
        return DB::transaction(function () use ($orderId, $status) {
            $order = Order::find($orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $order->update(['status' => $status]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    /**
     * Cancel order (admin)
     */
    public function cancelOrderAdmin(int $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items.product')->find($orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            // Restore product quantities if not already cancelled
            if ($order->status !== OrderStatus::CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }
            }

            $order->update(['status' => OrderStatus::CANCELLED]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }
}

