<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Sales\DTOs\Order\FormRequest\SearchOrdersAdminRequest;
use App\Domains\Sales\DTOs\Order\FormRequest\UpdateOrderStatusRequest;
use App\Domains\Sales\DTOs\Order\Requests\SearchOrdersAdminDTO;
use App\Domains\Sales\DTOs\Order\Requests\UpdateOrderStatusDTO;
use App\Domains\Sales\Services\OrderService;
use App\Http\Controllers\AppController;

class OrderController extends AppController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * [GET] /admin/orders
     */
    public function index(SearchOrdersAdminRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchOrdersAdminDTO::fromArray([
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

        $orders = $this->orderService->search($dto);

        return $this->success($orders);
    }

    /**
     * [GET] /admin/orders/{order_id}
     */
    public function show(int $order_id): ResponseDTO
    {
        $order = $this->orderService->getOrderAdminDetailsById($order_id);
        return $this->success($order);
    }

    /**
     * [GET] /admin/orders/{order_id}/status
     */
    public function status(int $order_id): ResponseDTO
    {
        $result = $this->orderService->getOrderAdminDetailsById($order_id);
        return $this->success($result);
    }

    /**
     * [PATCH] /admin/orders/{order_id}/status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $order_id): ResponseDTO
    {
        $dto = UpdateOrderStatusDTO::fromArray($request->validated(), $order_id);
        $result = $this->orderService->updateOrderStatus($dto);
        return $this->success($result);
    }

    /**
     * [DELETE] /admin/orders/{order_id}/cancel
     */
    public function cancel(int $order_Id): ResponseDTO
    {
        $result = $this->orderService->cancelOrderAdmin($order_Id);
        return $this->success($result);
    }
}
