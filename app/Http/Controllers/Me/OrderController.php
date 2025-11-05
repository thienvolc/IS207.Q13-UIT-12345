<?php

namespace App\Http\Controllers\Me;

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

        $orders = $this->orderService->getUserOrders(
            $request->input('status'),
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->successResponse($orders);
    }

    /**
     * POST /me/orders
     */
    public function place(PlaceOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->placeOrder($request->validated());
        return $this->createdResponse($order);
    }

    /**
     * GET /me/orders/{id}
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderDetails($id);
        return $this->successResponse($order);
    }

    /**
     * GET /me/orders/{id}/status
     */
    public function status(int $id): JsonResponse
    {
        $status = $this->orderService->getOrderStatus($id);
        return $this->successResponse($status);
    }

    /**
     * PATCH /me/orders/{id}/shipping
     */
    public function updateShipping(UpdateOrderShippingRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->updateShipping($id, $request->validated());
        return $this->successResponse($order);
    }

    /**
     * DELETE /me/orders/{id}/cancel
     */
    public function cancel(int $id): JsonResponse
    {
        $result = $this->orderService->cancelOrder($id);
        return $this->successResponse($result);
    }
}
