<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\DTOs\Commands\UpdateOrderStatusDTO;
use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        // Map status string to int if needed
        $statusValue = $this->mapStatusToInt($request->get('status'));

        // Build search DTO from request
        $searchDTO = new AdminSearchOrdersDTO(
            query: $request->get('q'),
            status: $statusValue,
            userId: $request->get('user_id') ? (int) $request->get('user_id') : null,
            start: $request->get('from'),
            end: $request->get('to'),
            min: $request->get('min') ? (float) $request->get('min') : null,
            max: $request->get('max') ? (float) $request->get('max') : null,
            page: $request->get('page', 1),
            size: $request->get('size', 20),
            sortField: $request->get('sort', 'created_at'),
            sortOrder: $request->get('order', 'desc'),
        );

        // Use service to search orders
        $ordersPage = $this->orderService->searchOrders($searchDTO);

        // Calculate stats
        $totalOrders = $ordersPage->total;
        $pendingOrders = $this->countOrdersByStatus(OrderStatus::PENDING_PAYMENT);
        $processingOrders = $this->countOrdersByStatus(OrderStatus::PROCESSING);
        $completedOrders = $this->countOrdersByStatus(OrderStatus::DELIVERED);
        $cancelledOrders = $this->countOrdersByStatus(OrderStatus::CANCELLED);

        return view('admin.orders.index', [
            'orders' => $ordersPage->data,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'pagination' => [
                'current' => $ordersPage->page,
                'total' => ceil($ordersPage->total / $ordersPage->size),
                'totalItems' => $ordersPage->total,
                'perPage' => $ordersPage->size,
            ],
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = $this->orderService->getOrderAdminDetailsById((int) $id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $statusValue = $this->mapStatusToInt($validated['status']);

        $updateDTO = new UpdateOrderStatusDTO(
            orderId: (int) $id,
            status: $statusValue,
        );

        $this->orderService->updateOrderStatus($updateDTO);

        return redirect()
            ->back()
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    /**
     * Map status string to OrderStatus constant.
     */
    private function mapStatusToInt(?string $status): ?int
    {
        if ($status === null) {
            return null;
        }

        return match ($status) {
            'pending' => OrderStatus::PENDING_PAYMENT,
            'paid' => OrderStatus::PAID,
            'processing' => OrderStatus::PROCESSING,
            'shipped' => OrderStatus::SHIPPED,
            'completed', 'delivered' => OrderStatus::DELIVERED,
            'refunded' => OrderStatus::REFUNDED,
            'returned' => OrderStatus::RETURNED,
            'cancelled' => OrderStatus::CANCELLED,
            default => is_numeric($status) ? (int) $status : null,
        };
    }

    /**
     * Count orders by status.
     */
    private function countOrdersByStatus(int $status): int
    {
        try {
            $dto = new AdminSearchOrdersDTO(
                query: null,
                status: $status,
                userId: null,
                start: null,
                end: null,
                min: null,
                max: null,
                page: 1,
                size: 1,
                sortField: 'created_at',
                sortOrder: 'desc',
            );

            $result = $this->orderService->searchOrders($dto);
            return $result->total;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
