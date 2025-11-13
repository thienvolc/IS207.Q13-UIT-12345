<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\Order\SearchOrdersAdminDto;
use App\Dtos\Order\UpdateOrderStatusDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Order\SearchOrdersAdminRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends AppController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * GET /admin/orders
     */
    public function index(SearchOrdersAdminRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchOrdersAdminDto::fromArray([
            'query' => $request->input('query'),
            'status' => $request->input('status'),
            'user_id' => $request->input('user_id'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
        ]);

        $orders = $this->orderService->searchOrdersAdmin($dto);

        return $this->success($orders);
    }

    /**
     * GET /admin/orders/{order_id}
     */
    public function show(int $order_id): JsonResponse
    {
        $order = $this->orderService->getOrderDetailsAdmin($order_id);
        return $this->success($order);
    }

    /**
     * GET /admin/orders/{order_id}/status
     */
    public function status(int $order_id): JsonResponse
    {
        $order = $this->orderService->getOrderDetailsAdmin($order_id);
        return $this->success([
            'order_id' => $order['order_id'],
            'status' => $order['status'],
        ]);
    }

    /**
     * PATCH /admin/orders/{order_id}/status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $order_id): JsonResponse
    {
        $dto = UpdateOrderStatusDto::fromArray($request->validated(), $order_id);
        $result = $this->orderService->updateOrderStatus($dto);
        return $this->success($result);
    }

    /**
     * DELETE /admin/orders/{order_id}/cancel
     */
    public function cancel(int $order_Id): JsonResponse
    {
        $result = $this->orderService->cancelOrderAdmin($order_Id);
        return $this->success($result);
    }
}
