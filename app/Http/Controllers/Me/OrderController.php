<?php

namespace App\Http\Controllers\Me;

use App\Dtos\Order\GetUserOrdersDto;
use App\Dtos\Order\PlaceOrderDto;
use App\Dtos\Order\UpdateOrderShippingDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Order\PlaceOrderRequest;
use App\Http\Requests\Order\GetOrdersRequest;
use App\Http\Requests\Order\UpdateOrderShippingRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends AppController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * GET /me/orders
     */
    public function index(GetOrdersRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetUserOrdersDto::fromArray([
            'status' => $request->input('status'),
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
        ]);

        $orders = $this->orderService->getUserOrders($dto);

        return $this->success($orders);
    }

    /**
     * POST /me/orders
     */
    public function place(PlaceOrderRequest $request): JsonResponse
    {
        $dto = PlaceOrderDto::fromArray($request->validated());
        $order = $this->orderService->placeOrder($dto);
        return $this->created($order);
    }

    /**
     * GET /me/orders/{order_id}
     */
    public function show(int $order_id): JsonResponse
    {
        $order = $this->orderService->getOrderDetails($order_id);
        return $this->success($order);
    }

    /**
     * GET /me/orders/{order_id}/status
     */
    public function status(int $order_id): JsonResponse
    {
        $status = $this->orderService->getOrderStatus($order_id);
        return $this->success($status);
    }

    /**
     * PATCH /me/orders/{order_id}/shipping
     */
    public function updateShipping(UpdateOrderShippingRequest $request, int $order_id): JsonResponse
    {
        $dto = UpdateOrderShippingDto::fromArray($request->validated(), $order_id);
        $order = $this->orderService->updateShipping($dto);
        return $this->success($order);
    }

    /**
     * DELETE /me/orders/{order_id}/cancel
     */
    public function cancel(int $order_id): JsonResponse
    {
        $result = $this->orderService->cancelOrder($order_id);
        return $this->success($result);
    }
}
