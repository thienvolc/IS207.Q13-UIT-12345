<?php

namespace App\Http\Controllers\Api\Admin\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Order\DTOs\FormRequest\AdminSearchOrdersRequest;
use App\Domains\Order\DTOs\FormRequest\UpdateOrderStatusRequest;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\AppController;

class OrderAdminController extends AppController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * [GET] /admin/orders
     */
    public function index(AdminSearchOrdersRequest $req): ResponseDTO
    {
        $orders = $this->orderService->searchOrders($req->toDTO());
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
    public function updateStatus(UpdateOrderStatusRequest $req, int $order_id): ResponseDTO
    {
        $result = $this->orderService->updateOrderStatus($req->toDTO($order_id));
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
