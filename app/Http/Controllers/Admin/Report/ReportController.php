<?php

namespace App\Http\Controllers\Admin\Report;

use App\Domains\Report\Services\ReportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportService $reportService
    ) {
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenueData(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $data = $this->reportService->getRevenueData($days);

        return response()->json($data);
    }

    public function topProductsData(Request $request)
    {
        $limit = (int) $request->get('limit', 10);
        $days = (int) $request->get('days', 30);

        $data = $this->reportService->getTopProducts($limit, $days);

        return response()->json($data);
    }

    public function customerData(Request $request)
    {
        $days = (int) $request->get('days', 30);

        $data = $this->reportService->getCustomerAnalytics($days);

        return response()->json($data);
    }
    public function exportProducts()
    {
        return $this->reportService->exportProducts();
    }

    public function exportOrders()
    {
        return $this->reportService->exportOrders();
    }

    public function exportRevenuePdf(Request $request)
    {
        $days = (int) $request->get('days', 30);
        return $this->reportService->exportRevenuePdf($days);
    }

    public function exportCustomers()
    {
        return $this->reportService->exportCustomers();
    }

    public function exportTransactions()
    {
        return $this->reportService->exportTransactions();
    }
}
