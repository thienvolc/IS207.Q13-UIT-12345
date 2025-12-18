<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Domains\Catalog\Services\ProductReadService;
use App\Domains\Identity\DTOs\User\Queries\SearchUsersDTO;
use App\Domains\Identity\Services\UserService;
use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ProductReadService $productReadService,
        private readonly OrderService $orderService,
        private readonly UserService $userService,
    ) {
    }

    // [GET] /admin
    public function index()
    {
        // Products stats
        $productsDTO = new AdminSearchProductsDTO(
            query: null,
            categoryIdOrSlug: null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            page: 1,
            size: 5,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        $productsPage = $this->productReadService->search($productsDTO);
        $totalProducts = $productsPage->total;
        $recentProducts = $productsPage->data;

        // Orders stats
        $ordersDTO = new AdminSearchOrdersDTO(
            query: null,
            status: null,
            userId: null,
            start: null,
            end: null,
            min: null,
            max: null,
            page: 1,
            size: 5,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        $ordersPage = $this->orderService->searchOrders($ordersDTO);
        $newOrders = $ordersPage->total;
        $recentOrders = $ordersPage->data;

        // Orders by status
        $pendingOrders = $this->countOrdersByStatus(OrderStatus::PENDING_PAYMENT);

        // Processing group: Paid + Processing + Shipped
        $processingOrders = $this->countOrdersByStatus(OrderStatus::PAID) +
            $this->countOrdersByStatus(OrderStatus::PROCESSING) +
            $this->countOrdersByStatus(OrderStatus::SHIPPED);

        $completedOrders = $this->countOrdersByStatus(OrderStatus::DELIVERED);

        // Cancelled group: Cancelled + Refunded + Returned
        $cancelledOrders = $this->countOrdersByStatus(OrderStatus::CANCELLED) +
            $this->countOrdersByStatus(OrderStatus::REFUNDED) +
            $this->countOrdersByStatus(OrderStatus::RETURNED);

        // Customers stats
        $customersDTO = new SearchUsersDTO(
            query: null,
            isAdmin: false,
            status: null,
            page: 1,
            size: 1,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        $customersPage = $this->userService->search($customersDTO);
        $customers = $customersPage->total;

        $revenueDTO = new AdminSearchOrdersDTO(
            query: null,
            status: OrderStatus::DELIVERED,
            userId: null,
            start: now()->subDays(30)->toDateString(),
            end: now()->toDateString(),
            min: null,
            max: null,
            page: 1,
            size: 1000,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        try {
            $revenueOrders = $this->orderService->searchOrders($revenueDTO);
            $revenue = 0;
            foreach ($revenueOrders->data as $order) {
                $revenue += $order->grandTotal ?? $order->grand_total ?? 0;
            }
        } catch (\Exception $e) {
            $revenue = 0;
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'recentProducts',
            'newOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'recentOrders',
            'customers',
            'revenue'
        ));
    }

    private function countOrdersByStatus(int $status): int
    {
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
    }

    public function chartData(\Illuminate\Http\Request $request)
    {
        $period = $request->input('period', 7); // 7, 30, 90
        $days = (int) $period;
        $endDate = now();
        $startDate = now()->subDays($days - 1);

        // Generate date range
        $labels = [];
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format('d/m');
            $currentDate->addDay();
        }

        // Query Revenue (Delivered Orders)
        $revenues = \App\Domains\Order\Entities\Order::query()
            ->where('status', \App\Domains\Order\Constants\OrderStatus::DELIVERED)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Query Orders (All Orders)
        $orders = \App\Domains\Order\Entities\Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        // Map data to labels
        $revenueData = [];
        $orderData = [];

        $loopDate = clone $startDate;
        while ($loopDate <= $endDate) {
            $dateStr = $loopDate->format('Y-m-d');
            $revenueData[] = $revenues[$dateStr] ?? 0;
            $orderData[] = $orders[$dateStr] ?? 0;
            $loopDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => $revenueData,
                ],
                [
                    'label' => 'Đơn hàng',
                    'data' => $orderData,
                ]
            ]
        ]);
    }
}
