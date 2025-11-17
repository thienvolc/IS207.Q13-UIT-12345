<?php

namespace App\Domains\Sales\Services;

use App\Applications\DTOs\Responses\OffsetPageResponseDTO;
use App\Applications\DTOs\Responses\PageResponseDTO;
use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\Constants\CartStatus;
use App\Domains\Sales\Constants\OrderStatus;
use App\Domains\Sales\DTOs\Order\Requests\GetUserOrdersDTO;
use App\Domains\Sales\DTOs\Order\Requests\PlaceOrderDTO;
use App\Domains\Sales\DTOs\Order\Requests\SearchOrdersAdminDTO;
use App\Domains\Sales\DTOs\Order\Requests\UpdateOrderShippingDTO;
use App\Domains\Sales\DTOs\Order\Requests\UpdateOrderStatusDTO;
use App\Domains\Sales\DTOs\Order\Responses\OrderResponseDTO;
use App\Domains\Sales\DTOs\Order\Responses\OrderStatusResponseDTO;
use App\Domains\Sales\DTOs\Order\Responses\ShortOrderResponseDTO;
use App\Domains\Sales\Entities\Cart;
use App\Domains\Sales\Entities\CartItem;
use App\Domains\Sales\Entities\Order;
use App\Domains\Sales\Repositories\CartItemRepository;
use App\Domains\Sales\Repositories\CartRepository;
use App\Domains\Sales\Repositories\OrderItemRepository;
use App\Domains\Sales\Repositories\OrderRepository;
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
    ) {}

    /**
     * @return OffsetPageResponseDTO<OrderResponseDTO>
     */
    public function searchUserOrders(GetUserOrdersDTO $dto): OffsetPageResponseDTO
    {
        $userId = $this->userId();

        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $orders = $this->orderRepository->searchOrdersForUser($pageable, $userId, $dto->status);

        return OffsetPageResponseDTO::fromPaginator($orders);
    }

    public function placeOrder(PlaceOrderDTO $dto): ShortOrderResponseDTO
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

            return ShortOrderResponseDTO::fromModel($order);
        });
    }

    public function getOrderDetailsById(int $orderId): OrderResponseDTO
    {
        $userId = $this->userId();
        $order = $this->orderRepository->getByIdAndUserWithItemsOrFail($orderId, $userId);

        return OrderResponseDTO::fromModel($order);
    }

    public function getOrderStatusById(int $orderId): OrderStatusResponseDTO
    {
        $userId = $this->userId();
        $order = $this->orderRepository->getByIdAndUserOrFail($userId, $orderId);

        return OrderStatusResponseDTO::fromModel($order);
    }

    public function updateShippingInfo(UpdateOrderShippingDTO $dto): OrderResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $order = $this->orderRepository->getByIdAndUserWithItemsOrFail($dto->orderId, $userId);
            $this->orderRules->assertCanUpdateShipping($order);

            $order->update($dto->toArray());

            return OrderResponseDTO::fromModel($order);
        });
    }

    public function cancelOrder(int $orderId): OrderStatusResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $orderId) {
            $order = $this->orderRepository->getLockedByIdAndUserWithProductsOrFail($orderId, $userId);
            $this->orderRules->assertCanCancelOrder($order);

            $this->stockReservationService->restoreAllProductStockInOrder($order);
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

            return OrderStatusResponseDTO::fromModel($order);
        });
    }

    /**
     * @return PageResponseDTO<OrderResponseDTO>
     */
    public function search(SearchOrdersAdminDTO $dto): PageResponseDTO
    {
        $filters = $dto->getFilters();
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $orders = $this->orderRepository->search($pageable, $filters);

        return PageResponseDTO::fromPaginator($orders);
    }

    public function getOrderAdminDetailsById(int $orderId): OrderResponseDTO
    {
        $order = $this->orderRepository->getByIdWithItemsOrFail($orderId);
        return OrderResponseDTO::fromModel($order);
    }

    public function updateOrderStatus(UpdateOrderStatusDTO $dto): OrderStatusResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->orderRepository->getByIdOrFail($dto->orderId);

            $order->update(['status' => $dto->status]);

            return OrderStatusResponseDTO::fromModel($order);
        });
    }

    public function cancelOrderAdmin(int $orderId): OrderStatusResponseDTO
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->orderRepository->getByIdWithItemsOrFail($orderId);

            if ($order->status !== OrderStatus::CANCELLED) {
                $this->stockReservationService->restoreAllProductStockInOrder($order);
            }
            $order->update(['status' => OrderStatus::CANCELLED]);

            // TODO: Process refund if payment was made

            return OrderStatusResponseDTO::fromModel($order);
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
        $order = $this->orderRepository->create([
            'user_id'       => $cart->user_id,
            'subtotal'      => $orderPrice->subtotal,
            'tax'           => $orderPrice->tax,
            'shipping'      => $orderPrice->shipping,
            'total'         => $orderPrice->subtotal + $orderPrice->tax + $orderPrice->shipping,
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
