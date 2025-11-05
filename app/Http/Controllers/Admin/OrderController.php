<?php

namespace App\Http\Controllers\Admin;

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

        $filters = [
            'query' => $request->input('query'),
            'status' => $request->input('status'),
            'user_id' => $request->input('user_id'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
        ];

        $orders = $this->orderService->searchOrdersAdmin(
            $filters,
            $request->getPage(),
            $request->getSize(),
            $sortField,
            $sortOrder
        );

        return $this->successResponse($orders);
    }

    /**
     * GET /admin/orders/{id}
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderDetailsAdmin($id);
        return $this->successResponse($order);
    }

    /**
     * GET /admin/orders/{id}/status
     */
    public function status(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderDetailsAdmin($id);
        return $this->successResponse([
            'order_id' => $order['order_id'],
            'status' => $order['status'],
        ]);
    }

    /**
     * PATCH /admin/orders/{id}/status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $id): JsonResponse
    {
        $result = $this->orderService->updateOrderStatus($id, $request->input('status'));
        return $this->successResponse($result);
    }
}
