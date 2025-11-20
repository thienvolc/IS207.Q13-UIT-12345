<?php

namespace App\Http\Controllers\Api\Public\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Order\DTOs\FormRequest\PlaceOrderRequest;
use App\Domains\Order\DTOs\FormRequest\UpdateOrderShippingRequest;
use App\Domains\Order\DTOs\FormRequest\UserSearchOrdersRequest;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\AppController;

class UserOrderController extends AppController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * GET /me/orders
     */
    public function index(UserSearchOrdersRequest $req): ResponseDTO
    {
        $orders = $this->orderService->searchUserOrders($req->toDTO());
        return $this->success($orders);
    }

    /**
     * POST /me/orders
     */
    public function place(PlaceOrderRequest $req): ResponseDTO
    {
        $order = $this->orderService->placeOrder($req->toDTO());
        return $this->created($order);
    }

    /**
     * GET /me/orders/{order_id}
     */
    public function show(int $order_id): ResponseDTO
    {
        $order = $this->orderService->getOrderDetailsById($order_id);
        return $this->success($order);
    }

    /**
     * GET /me/orders/{order_id}/status
     */
    public function status(int $order_id): ResponseDTO
    {
        $status = $this->orderService->getOrderStatusById($order_id);
        return $this->success($status);
    }

    /**
     * PATCH /me/orders/{order_id}/shipping
     */
    public function updateShipping(UpdateOrderShippingRequest $req, int $order_id): ResponseDTO
    {
        $order = $this->orderService->updateShippingInfo($req->toDTO($order_id));
        return $this->success($order);
    }

    /**
     * DELETE /me/orders/{order_id}/cancel
     */
    public function cancel(int $order_id): ResponseDTO
    {
        $result = $this->orderService->cancelOrder($order_id);
        return $this->success($result);
    }
}
