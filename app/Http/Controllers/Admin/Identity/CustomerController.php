<?php

namespace App\Http\Controllers\Admin\Identity;

use App\Domains\Identity\DTOs\User\Queries\SearchUsersDTO;
use App\Domains\Identity\Services\UserService;
use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        // Map status string to int if needed
        $statusValue = null;
        if ($request->filled('status')) {
            $statusValue = (int) $request->get('status');
        }

        // Build search DTO from request
        $searchDTO = new SearchUsersDTO(
            query: $request->get('q'),
            isAdmin: false, // Only show customers, not admins
            status: $statusValue,
            page: $request->get('page', 1),
            size: $request->get('size', 20),
            sortField: $request->get('sort', 'created_at'),
            sortOrder: $request->get('order', 'desc'),
        );

        // Use service to search users
        $usersPage = $this->userService->search($searchDTO);

        // Calculate stats
        $totalCustomers = $usersPage->total;

        // Active customers (status = 1)
        $activeSearchDTO = new SearchUsersDTO(
            query: null,
            isAdmin: false,
            status: 1,
            page: 1,
            size: 1,
            sortField: 'created_at',
            sortOrder: 'desc',
        );
        try {
            $activeCustomers = $this->userService->search($activeSearchDTO)->total;
        } catch (\Exception $e) {
            $activeCustomers = 0;
        }

        // New this month - simplified (should be a service method)
        $newThisMonth = 0; // TODO: Add date filter to SearchUsersDTO

        return view('admin.customers.index', [
            'customers' => $usersPage->data,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'newThisMonth' => $newThisMonth,
            'pagination' => [
                'current' => $usersPage->page,
                'total' => ceil($usersPage->total / $usersPage->size),
                'totalItems' => $usersPage->total,
                'perPage' => $usersPage->size,
            ],
        ]);
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        // Get customer details
        $customer = $this->userService->getUserById((int) $id);

        // Get customer orders
        $ordersDTO = new AdminSearchOrdersDTO(
            query: null,
            status: null,
            userId: (int) $id,
            start: null,
            end: null,
            min: null,
            max: null,
            page: 1,
            size: 10,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        try {
            $ordersPage = $this->orderService->searchOrders($ordersDTO);
            $orders = $ordersPage->data;
        } catch (\Exception $e) {
            $orders = [];
        }

        // Calculate total spent
        $totalSpent = 0;
        foreach ($orders as $order) {
            // Only count completed orders (DELIVERED = 5)
            if (isset($order->status) && in_array($order->status, [5])) {
                $totalSpent += $order->grandTotal ?? $order->grand_total ?? 0;
            }
        }

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders' => $orders,
            'totalSpent' => $totalSpent,
        ]);
    }
}
