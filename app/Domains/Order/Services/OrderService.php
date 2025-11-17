<?php

namespace App\Domains\Order\Services;

use App\Domains\Cart\Constants\CartStatus;
use App\Domains\Cart\Entities\Cart;
use App\Domains\Cart\Entities\CartItem;
use App\Domains\Cart\Repositories\CartItemRepository;
use App\Domains\Cart\Repositories\CartRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Common\DTOs\OffsetPageResponseDTO;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Domains\Inventory\Services\ProductAvailabilityService;
use App\Domains\Inventory\Services\StockReservationService;
use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\DTOs\Commands\PlaceOrderDTO;
use App\Domains\Order\DTOs\Commands\UpdateOrderShippingDTO;
use App\Domains\Order\DTOs\Commands\UpdateOrderStatusDTO;
use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Domains\Order\DTOs\Queries\UserSearchOrdersDTO;
use App\Domains\Order\DTOs\Responses\OrderDTO;
use App\Domains\Order\DTOs\Responses\OrderStatusDTO;
use App\Domains\Order\DTOs\Responses\OrderSummaryDTO;
use App\Domains\Order\Entities\Order;
use App\Domains\Order\Mappers\OrderMapper;
use App\Domains\Order\Repositories\OrderItemRepository;
use App\Domains\Order\Repositories\OrderRepository;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

readonly class OrderService
{
    public function __construct(
        private ProductAvailabilityService $productAvailabilityService,
        private StockReservationService    $stockReservationService,
        private OrderRules                 $orderRules,
        private OrderRepository            $orderRepository,
        private OrderItemRepository        $orderItemRepository,
        private CartRepository             $cartRepository,
        private CartItemRepository         $cartItemRepository,
        private PricingService             $pricingService,
        private OrderMapper                $orderMapper,
    ) {}

    /**
     * @return OffsetPageResponseDTO<OrderDTO>
     */
    public function searchUserOrders(UserSearchOrdersDTO $dto): OffsetPageResponseDTO
    {
        $userId = $this->userId();

        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $orders = $this->orderRepository->searchOrdersForUser($pageable, $userId, $dto->status);

        return OffsetPageResponseDTO::fromPaginator($orders);
    }

    public function placeOrder(PlaceOrderDTO $dto): OrderSummaryDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->getCheckoutCartByIdAndUserOrFail($dto->cartId, $userId);
            $this->assertCartNotEmpty($cart);

            $this->productAvailabilityService->lockStockAndValidateAvailability($cart->items);

            // TODO: validate stock reserve

            $order = $this->createOrder($cart, $dto->promo);
            $this->completeCart($cart);

            // TODO: Init payment process here

            return $this->orderMapper->toSummaryDTO($order);
        });
    }

    public function getOrderDetailsById(int $orderId): OrderDTO
    {
        $userId = $this->userId();
        $order = $this->orderRepository->getByIdAndUserWithItemsOrFail($orderId, $userId);

        return $this->orderMapper->toDTO($order);
    }

    public function getOrderStatusById(int $orderId): OrderStatusDTO
    {
        $userId = $this->userId();
        $order = $this->orderRepository->getByIdAndUserOrFail($userId, $orderId);

        return $this->orderMapper->toStatusDTO($order);
    }

    public function updateShippingInfo(UpdateOrderShippingDTO $dto): OrderDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $order = $this->orderRepository->getByIdAndUserWithItemsOrFail($dto->orderId, $userId);
            $this->orderRules->assertCanUpdateShipping($order);

            $order->update($dto->toArray());

            return $this->orderMapper->toDTO($order);
        });
    }

    public function cancelOrder(int $orderId): OrderStatusDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $orderId) {
            $order = $this->orderRepository->getLockedByIdAndUserWithProductsOrFail($orderId, $userId);
            $this->orderRules->assertCanCancelOrder($order);

            $this->stockReservationService->restoreAllProductStockInOrder($order);
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

            return $this->orderMapper->toStatusDTO($order);
        });
    }

    /**
     * @return PageResponseDTO<OrderDTO>
     */
    public function searchOrders(AdminSearchOrdersDTO $dto): PageResponseDTO
    {
        $filters = $dto->getFilters();
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $orders = $this->orderRepository->searchOrders($pageable, $filters);

        return PageResponseDTO::fromPaginator($orders);
    }

    public function getOrderAdminDetailsById(int $orderId): OrderStatusDTO
    {
        $order = $this->orderRepository->getByIdWithItemsOrFail($orderId);
        return $this->orderMapper->toStatusDTO($order);
    }

    public function updateOrderStatus(UpdateOrderStatusDTO $dto): OrderStatusDTO
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->orderRepository->getByIdOrFail($dto->orderId);

            $order->update(['status' => $dto->status]);

            return $this->orderMapper->toStatusDTO($order);
        });
    }

    public function cancelOrderAdmin(int $orderId): OrderStatusDTO
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->orderRepository->getByIdWithItemsOrFail($orderId);

            if ($order->status !== OrderStatus::CANCELLED) {
                $this->stockReservationService->restoreAllProductStockInOrder($order);
            }
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

            return $this->orderMapper->toStatusDTO($order);
        });
    }

    private function userId(): int
    {
        return Auth::id();
    }

    private function assertCartNotEmpty(Cart $cart): void
    {
        if ($cart->items->isEmpty()) {
            throw new BusinessException(ResponseCode::BAD_REQUEST);
        }
    }

    private function createOrder(Cart $cart, ?string $promo): Order
    {
        $orderPrice = $this->pricingService->calculate($cart, $promo);
        $total = $orderPrice->subtotal + $orderPrice->tax + $orderPrice->shipping;
        $order = $this->orderRepository->create([
            'user_id'       => $cart->user_id,
            'subtotal'      => $orderPrice->subtotal,
            'tax'           => $orderPrice->tax,
            'shipping'      => $orderPrice->shipping,
            'total'         => $total,
            'discount_total'=> $orderPrice->discountTotal,
            'promo'         => $promo,
            'discount'      => $orderPrice->promoDiscount,
            'grand_total'   => $orderPrice->grandTotal,
            'first_name'    => $cart->first_name,
            'middle_name'   => $cart->middle_name,
            'last_name'     => $cart->last_name,
            'phone'         => $cart->phone,
            'email'         => $cart->email,
            'line1'         => $cart->line1,
            'line2'         => $cart->line2,
            'city'          => $cart->city,
            'province'      => $cart->province,
            'country'       => $cart->country,
            'note'          => $cart->note,
            'status'        => OrderStatus::PENDING_PAYMENT,
            'orders_at'     => now(),
        ]);

        $orderId = $order->order_id;
        foreach ($cart->items as $cartItem) {
            $this->createOrderItem($orderId, $cartItem);
            $this->stockReservationService->reserveProductStock($cartItem);
        }

        return $order;
    }

    private function createOrderItem(int $orderId, CartItem $cartItem): void
    {
        $this->orderItemRepository->create([
            'order_id'  => $orderId,
            'product_id'=> $cartItem->product_id,
            'price'     => $cartItem->price,
            'discount'  => $cartItem->discount,
            'quantity'  => $cartItem->quantity,
            'note'      => $cartItem->note,
        ]);
    }

    private function completeCart(Cart $cart): void
    {
        $cart->update(['status' => CartStatus::COMPLETED]);
        $this->removeCheckedOutCartItemsForUserActiveCart($cart->user_id, $cart->items);
    }

    private function removeCheckedOutCartItemsForUserActiveCart($userId, Collection $cartItems): void
    {
        $productIds = $cartItems
            ->map(fn(CartItem $i) => $i->product_id)
            ->toArray();

        $this->cartItemRepository->deleteAllInActiveCartByUserIdAndProductIds($userId, $productIds);
    }
}
