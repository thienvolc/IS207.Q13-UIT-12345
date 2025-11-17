<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Sales\DTOs\Order\FormRequest\GetOrdersRequest;
use App\Domains\Sales\DTOs\Order\FormRequest\PlaceOrderRequest;
use App\Domains\Sales\DTOs\Order\FormRequest\UpdateOrderShippingRequest;
use App\Domains\Sales\DTOs\Order\Requests\GetUserOrdersDTO;
use App\Domains\Sales\DTOs\Order\Requests\PlaceOrderDTO;
use App\Domains\Sales\DTOs\Order\Requests\UpdateOrderShippingDTO;
use App\Domains\Sales\Services\OrderService;
use App\Http\Controllers\AppController;

class UserOrderController extends AppController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * GET /me/orders
     */
    public function index(GetOrdersRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetUserOrdersDTO::fromArray([
            'status' => $request->input('status'),
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
        ]);

        $orders = $this->orderService->searchUserOrders($dto);

        return $this->success($orders);
    }

    /**
     * POST /me/orders
     */
    public function place(PlaceOrderRequest $request): ResponseDTO
    {
        $dto = PlaceOrderDTO::fromArray($request->validated());
        $order = $this->orderService->placeOrder($dto);
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
    public function updateShipping(UpdateOrderShippingRequest $request, int $order_id): ResponseDTO
    {
        $dto = UpdateOrderShippingDTO::fromArray($request->validated(), $order_id);
        $order = $this->orderService->updateShippingInfo($dto);
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
