<?php

namespace App\Services;

use App\Constants\CartStatus;
use App\Constants\OrderStatus;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Dtos\Order\GetUserOrdersDto;
use App\Dtos\Order\PlaceOrderDto;
use App\Dtos\Order\SearchOrdersAdminDto;
use App\Dtos\Order\UpdateOrderShippingDto;
use App\Dtos\Order\UpdateOrderStatusDto;
use App\Exceptions\BusinessException;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShortOrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\CartItemRepository;
use App\Repositories\CartRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Utils\PaginationUtil;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class OrderService
{
    public function __construct(
        private OrderRepository     $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private CartRepository      $cartRepository,
        private CartItemRepository  $cartItemRepository,
        private ProductRepository   $productRepository
    )
    {
    }

    public function getUserOrders(GetUserOrdersDto $dto): array
    {
        $userId = $this->getAuthUserId();

        $totalCount = $this->orderRepository->countUserOrders($userId, $dto->status);
        $orders = $this->orderRepository->getUserOrders(
            $userId,
            $dto->status,
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit);

        return PaginationUtil::fromOffsetLimit(
            ShortOrderResource::collection($orders),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function placeOrder(PlaceOrderDto $dto): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->findCheckedOutCart($userId, $dto->cartId);

            if (!$cart) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $this->assertCartNotEmpty($cart);

            $products = $this->lockAndValidateProducts($cart);
            $calculations = $this->calculateOrderTotals($cart, $dto->promo);

            $order = $this->createOrder($userId, $cart, $calculations, $dto->promo);
            $this->createOrderItems($order, $cart, $products);
            $this->completeCart($cart);

            $this->deleteSelectedItemsInActiveCart($userId, $products);
            return ShortOrderResource::transform($order);
        });

    }

    public function getOrderDetails(int $orderId): array
    {
        $userId = $this->getAuthUserId();
        $order = $this->orderRepository->findUserOrder($userId, $orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return OrderResource::transform($order);
    }

    public function getOrderStatus(int $orderId): array
    {
        $userId = $this->getAuthUserId();
        $order = $this->orderRepository->findUserOrderWithoutRelations($userId, $orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return [
            'order_id' => $order->order_id,
            'status' => $order->status,
        ];
    }

    public function updateShipping(UpdateOrderShippingDto $dto): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $order = $this->orderRepository->findUserOrderWithoutRelations($userId, $dto->orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

// TODO     $this->assertCanUpdateShipping($order);

            $this->orderRepository->update($order, $dto->toArray());
            $order->load('items');

            return OrderResource::transform($order);
        });
    }

    public function cancelOrder(int $orderId): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $orderId) {
            $order = $this->orderRepository->findAndLockUserOrder($userId, $orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

// TODO     $this->assertNotAlreadyCancelled($order);
            $this->assertCanCancelOrder($order);

            $this->orderItemRepository->restoreProductQuantities($order);
            $this->orderRepository->update($order, ['status' => OrderStatus::CANCELLED]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    public function searchOrdersAdmin(SearchOrdersAdminDto $dto): array
    {
        $filters = $dto->getFilters();
        $offset = ($dto->page - 1) * $dto->size;

        $totalCount = $this->orderRepository->countWithFilters($filters);
        $orders = $this->orderRepository->searchWithFilters(
            $filters,
            $dto->sortField,
            $dto->sortOrder,
            $offset,
            $dto->size);

        return PaginationUtil::fromPageSize(
            OrderResource::collection($orders),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function getOrderDetailsAdmin(int $orderId): array
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return OrderResource::transform($order);
    }

    public function updateOrderStatus(UpdateOrderStatusDto $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->orderRepository->findByIdWithoutRelations($dto->orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $this->orderRepository->update($order, ['status' => $dto->status]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    public function cancelOrderAdmin(int $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->orderRepository->findById($orderId);

            if (!$order) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            if ($order->status !== OrderStatus::CANCELLED) {
                $this->orderItemRepository->restoreProductQuantities($order);
            }

            $this->orderRepository->update($order, ['status' => OrderStatus::CANCELLED]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    private function getAuthUserId(): int
    {
        return Auth::id();
    }

    private function assertCartNotEmpty(Cart $cart): void
    {
        if ($cart->items->isEmpty()) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Cart is empty'
            ]);
        }
    }

    private function lockAndValidateProducts(Cart $cart)
    {
        $productIds = $cart->items->pluck('product_id')->toArray();
        $products = $this->productRepository->findByIdsWithLock($productIds);

        foreach ($cart->items as $item) {
            /** @var Product $product */
            $product = $products->get($item->product_id);

            if (!$product) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => "Product {$item->product_id} is not available"
                ]);
            }

            $this->validateProductAvailability($product, $item->quantity);
        }

        return $products;
    }

    private function validateProductAvailability(Product $product, int $requestedQuantity): void
    {
        if ($product->status !== ProductStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => "Product {$product->product_id} is not available"
            ]);
        }

        if ($product->quantity < $requestedQuantity) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => "Not enough stock for product {$product->title}",
                'available' => $product->quantity,
                'requested' => $requestedQuantity,
            ]);
        }
    }

    private function calculateOrderTotals(Cart $cart, ?string $promo): array
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discountTotal = $cart->items->sum(function ($item) {
            return $item->discount * $item->quantity;
        });

        $netAmount = $subtotal - $discountTotal;
        $tax = $netAmount * 0.1;
        $shipping = 30000;

        $promoDiscount = 0;
        if (!empty($promo)) {
            $promoDiscount = $netAmount * 0.05;
        }

        $grandTotal = $netAmount - $promoDiscount + $tax + $shipping;

        if ($grandTotal < 0) {
            $grandTotal = 0;
        }

        return [
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'promo_discount' => $promoDiscount,
            'grand_total' => $grandTotal,
        ];
    }

    private function createOrder(
        int     $userId,
        Cart    $cart,
        array   $calculations,
        ?string $promo
    ): Order
    {
        return $this->orderRepository->create([
            'user_id' => $userId,
            'subtotal' => $calculations['subtotal'],
            'tax' => $calculations['tax'],
            'shipping' => $calculations['shipping'],
            'total' => $calculations['subtotal'] + $calculations['tax'] + $calculations['shipping'],
            'discount_total' => $calculations['discount_total'],
            'promo' => $promo,
            'discount' => $calculations['promo_discount'],
            'grand_total' => $calculations['grand_total'],
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
    }

    private function createOrderItems(Order $order, Cart $cart, $products): void
    {
        foreach ($cart->items as $cartItem) {
            $this->orderItemRepository->create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'price' => $cartItem->price,
                'discount' => $cartItem->discount,
                'quantity' => $cartItem->quantity,
                'note' => $cartItem->note,
            ]);

            $product = $products->get($cartItem->product_id);
            $updated = $this->productRepository->decrementQuantity($product->product_id, $cartItem->quantity);

            if (!$updated) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => "Failed to reserve stock for {$product->title}. Product may have been purchased by another user."
                ]);
            }
        }
    }

    private function completeCart(Cart $cart): void
    {
        $cart->update(['status' => CartStatus::COMPLETED]);
        $this->cartItemRepository->deleteByCartId($cart->cart_id);
    }

    private function assertCanCancelOrder(Order $order): void
    {
        if (!$this->isCancelableStatus($order->status)) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Cannot cancel this order. Order status does not allow cancellation.',
                'current_status' => $order->status,
            ]);
        }
    }

    private function isCancelableStatus(string $status): bool
    {
        $cancelableStatuses = [
            OrderStatus::PENDING_PAYMENT,
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
        ];

        return in_array($status, $cancelableStatuses);
    }

    private function deleteSelectedItemsInActiveCart(int $userId, Collection $products): void
    {
        $cart = $this->cartRepository->findActive($userId);
        $productIds = $products->map(function (Product $product) {
            return $product->product_id;
        })->toArray();
        $this->cartItemRepository->deleteByCartAndProductIds($cart->cart_id, $productIds);
    }

}
