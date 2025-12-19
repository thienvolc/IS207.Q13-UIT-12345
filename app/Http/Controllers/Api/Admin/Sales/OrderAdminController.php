<?php

namespace App\Http\Controllers\Api\Admin\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Order\DTOs\FormRequest\AdminSearchOrdersRequest;
use App\Domains\Order\DTOs\FormRequest\UpdateOrderStatusRequest;
use App\Domains\Order\Services\OrderService;
use App\Domains\Payment\Services\RefundService;
use App\Http\Controllers\AppController;
use Illuminate\Http\Request;

class OrderAdminController extends AppController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly RefundService $refundService,
    ) {
    }

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

    /**
     * [POST] /admin/orders/{order_id}/refund
     */
    public function refund(Request $request, int $order_id): ResponseDTO
    {
        $result = $this->refundService->refundOrder($order_id, $request->ip());
        return $this->success($result);
    }
}
