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
use App\Models\CartItem;
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

class OrderService
{
    public function __construct(
        private readonly OrderRepository     $orderRepository,
        private readonly OrderItemRepository $orderItemRepository,
        private readonly CartRepository      $cartRepository,
        private readonly CartItemRepository  $cartItemRepository,
        private readonly ProductRepository   $productRepository,
        private readonly float               $DEFAULT_TAX_RATE = 0.1,
        private readonly int                 $DEFAULT_SHIPPING_FEE = 30000,
        private readonly float               $DEFAULT_PROMO_DISCOUNT_RATE = 0.05
    ) {}

    public function getUserOrders(GetUserOrdersDto $dto): array
    {
        $userId = $this->getCurrentUserId();

        $totalCount = $this->orderRepository->countUserOrders($userId, $dto->status);
        $orders = $this->orderRepository->findUserOrders(
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
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->findCheckedOutCartWithItemsOrFail($userId, $dto->cartId);
            $this->assertCartNotEmpty($cart);

            $order = $this->createOrder($userId, $cart, $dto->promo);
            $this->createOrderItems($order, $cart);
            $this->completeCart($cart);
            $this->removeOrderedItemsInUserCart($cart);

            // TODO: Init payment process here

            return ShortOrderResource::transform($order);
        });
    }

    public function getOrderDetails(int $orderId): array
    {
        $userId = $this->getCurrentUserId();
        $order = $this->orderRepository->findUserOrderWithItems($userId, $orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return OrderResource::transform($order);
    }

    public function getOrderStatus(int $orderId): array
    {
        $userId = $this->getCurrentUserId();
        $order = $this->findUserOrderOrFail($userId, $orderId);

        return [
            'order_id' => $order->order_id,
            'status' => $order->status,
        ];
    }

    public function updateShipping(UpdateOrderShippingDto $dto): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $order = $this->findUserOrderOrFail($userId, $dto->orderId);

            $this->assertCanUpdateShipping($order);

            $order->update($dto->toArray());
            $order->load('items');

            return OrderResource::transform($order);
        });
    }

    public function cancelOrder(int $orderId): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $orderId) {
            $order = $this->findAndLockUserOrderOrFail($userId, $orderId);

            $this->assertCanCancelOrder($order);

            $this->orderItemRepository->restoreProductQuantities($order);
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

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
        $order = $this->findOrderWithItemsByIdOrFail($orderId);

        return OrderResource::transform($order);
    }

    public function updateOrderStatus(UpdateOrderStatusDto $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->findOrderByIdOrFail($dto->orderId);
            $order->update(['status' => $dto->status]);

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    public function cancelOrderAdmin(int $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->findOrderWithItemsByIdOrFail($orderId);

            if ($order->status !== OrderStatus::CANCELLED) {
                $this->orderItemRepository->restoreProductQuantities($order);
            }
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

            return [
                'order_id' => $order->order_id,
                'status' => $order->status,
            ];
        });
    }

    private function getCurrentUserId(): int
    {
        return Auth::id();
    }

    private function findCheckedOutCartWithItemsOrFail(int $userId, int $cartId): Cart
    {
        $cart = $this->cartRepository->findCheckedOutWithItemsByUserIdAndCartId($userId, $cartId);

        if (!$cart) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $cart;
    }

    private function assertCartNotEmpty(Cart $cart): void
    {
        if ($cart->items->isEmpty()) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Cart is empty'
            ]);
        }
    }

    private function lockAndValidateProductsInCart(Cart $cart): \Illuminate\Support\Collection
    {
        $productIds = $cart->items->pluck('product_id')->toArray();
        $products = $this->productRepository->findByIdsWithLock($productIds);

        foreach ($cart->items as $item) {
            /** @var Product $product */
            $product = $products->get($item->product_id);

            $this->validateProductAvailable($product, $item->quantity);
        }

        return $products;
    }

    private function validateProductAvailable(Product $product, int $requestedQuantity): void
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

    private function createOrder(
        int     $userId,
        Cart    $cart,
        ?string $promo
    ): Order
    {
        $calculations = $this->calculateOrderTotals($cart, $promo);

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

    private function calculateOrderTotals(Cart $cart, ?string $promo): array
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $discountTotal = $cart->items->sum(function ($item) {
            return $item->discount * $item->quantity;
        });

        $netAmount = $subtotal - $discountTotal;
        $tax = $netAmount * $this->DEFAULT_TAX_RATE;
        $shipping = $this->DEFAULT_SHIPPING_FEE;

        $promoDiscount = 0;
        if (!empty($promo)) {
            $promoDiscount = $netAmount * $this->DEFAULT_PROMO_DISCOUNT_RATE;
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

    private function createOrderItems(Order $order, Cart $cart): void
    {
        $products = $this->lockAndValidateProductsInCart($cart);

        foreach ($cart->items as $cartItem) {
            $this->orderItemRepository->create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'price' => $cartItem->price,
                'discount' => $cartItem->discount,
                'quantity' => $cartItem->quantity,
                'note' => $cartItem->note,
            ]);

            /** @var Product  $product */
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

    private function removeOrderedItemsInUserCart(Cart $checkedOutCart): void
    {
        $userId = $checkedOutCart->user_id;
        $productIds = $checkedOutCart->items->map(function (CartItem $item) {
            return $item->product_id;
        })->toArray();

        $activeCart = $this->cartRepository->findActiveByUserId($userId);
        $this->cartItemRepository->deleteByCartIdAndProductIds($activeCart->cart_id, $productIds);
    }

    private function findUserOrderOrFail(int $userId, int $orderId)
    {
        $order = $this->orderRepository->findUserOrder($userId, $orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $order;
    }

    private function assertCanUpdateShipping(Order $order): void
    {
        if (!$this->isUpdatableOrder($order)) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Cannot update this order.',
                'current_status' => $order->status,
            ]);
        }
    }

    private function isUpdatableOrder(Order $order): bool
    {
        return $this->isUpdatableStatus($order->status) &&
            !$this->isPastUpdateDeadline($order->orders_at);
    }

    private function isUpdatableStatus(int $status): bool
    {
        $updatableStatuses = [
            OrderStatus::PENDING_PAYMENT,
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
        ];

        return in_array($status, $updatableStatuses);
    }

    private function isPastUpdateDeadline($ordersAt): bool
    {
        $updateDeadline = $ordersAt->copy()->addHours(2);

        return !now()->greaterThan($updateDeadline);
    }

    private function findAndLockUserOrderOrFail(int $userId, int $orderId): Order
    {
        $order = $this->orderRepository->findAndLockUserOrder($userId, $orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $order;
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

    private function findOrderWithItemsByIdOrFail(int $orderId): Order
    {
        $order = $this->orderRepository->findWithItemsById($orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $order;
    }

    private function findOrderByIdOrFail(int $orderId): Order
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $order;
    }
}
