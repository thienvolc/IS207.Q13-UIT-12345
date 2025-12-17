<?php

namespace App\Domains\Report\Services;

use App\Domains\Order\Constants\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Identity\Entities\User;
use App\Domains\Transaction\Entities\Transaction;

class ReportService
{
    public function exportProducts()
    {
        $products = Product::with('categories')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Tên sản phẩm');
        $sheet->setCellValue('C1', 'SKU');
        $sheet->setCellValue('D1', 'Danh mục');
        $sheet->setCellValue('E1', 'Giá');
        $sheet->setCellValue('F1', 'Tồn kho');
        $sheet->setCellValue('G1', 'Trạng thái');
        $sheet->setCellValue('H1', 'Ngày tạo');

        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->product_id); // Use correct PK
            $sheet->setCellValue('B' . $row, $product->title);
            $sheet->setCellValue('C' . $row, $product->sku);
            $sheet->setCellValue('D' . $row, $product->categories->first()->title ?? 'N/A'); // Use categories collection
            $sheet->setCellValue('E' . $row, $product->price);
            $sheet->setCellValue('F' . $row, $product->quantity);
            $sheet->setCellValue('G' . $row, $product->status == 1 ? 'Hiện' : 'Ẩn');
            $sheet->setCellValue('H' . $row, $product->created_at->format('d/m/Y H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'products-' . date('Y-m-d') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            if (ob_get_length())
                ob_end_clean();
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportOrders()
    {
        $orders = Order::with(['user'])->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Mã Đơn');
        $sheet->setCellValue('B1', 'Khách hàng');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Tổng tiền (VNĐ)');
        $sheet->setCellValue('E1', 'Trạng thái');
        $sheet->setCellValue('F1', 'Ngày đặt');

        $row = 2;
        foreach ($orders as $order) {
            $statusText = match ($order->status) {
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý',
                'delivered', 'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
                default => $order->status,
            };

            $sheet->setCellValue('A' . $row, $order->order_id);
            $sheet->setCellValue('B' . $row, $order->user->name ?? 'Khách vãng lai');
            $sheet->setCellValue('C' . $row, $order->user->email ?? $order->email ?? 'N/A');
            $sheet->setCellValue('D' . $row, $order->grand_total);
            $sheet->setCellValue('E' . $row, $statusText);
            $sheet->setCellValue('F' . $row, $order->created_at->format('d/m/Y H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'orders-' . date('Y-m-d') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            if (ob_get_length())
                ob_end_clean();
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportCustomers()
    {
        $customers = User::where('is_admin', false)->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Họ tên');
        $sheet->setCellValue('D1', 'SĐT');
        $sheet->setCellValue('E1', 'Trạng thái');
        $sheet->setCellValue('F1', 'Ngày đăng ký');

        $row = 2;
        foreach ($customers as $customer) {
            $sheet->setCellValue('A' . $row, $customer->user_id);
            $sheet->setCellValue('B' . $row, $customer->email);
            $sheet->setCellValue('C' . $row, $customer->name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $customer->phone ?? 'N/A');
            $sheet->setCellValue('E' . $row, $customer->status == 1 ? 'Hoạt động' : 'Khóa');
            $sheet->setCellValue('F' . $row, $customer->created_at->format('d/m/Y H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'customers-' . date('Y-m-d') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            if (ob_get_length())
                ob_end_clean();
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportTransactions()
    {
        $transactions = Transaction::with('order')->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Mã GD');
        $sheet->setCellValue('B1', 'Mã Đơn');
        $sheet->setCellValue('C1', 'Nội dung');
        $sheet->setCellValue('D1', 'Số tiền');
        $sheet->setCellValue('E1', 'Loại');
        $sheet->setCellValue('F1', 'Trạng thái');
        $sheet->setCellValue('G1', 'Ngày tạo');

        $row = 2;
        foreach ($transactions as $transaction) {
            $typeText = match ($transaction->type) {
                1 => 'Thanh toán',
                2 => 'Hoàn tiền',
                default => 'Khác'
            };

            $statusText = match ($transaction->status) {
                1 => 'Thành công',
                0 => 'Thất bại',
                2 => 'Chờ xử lý',
                default => $transaction->status,
            };

            $sheet->setCellValue('A' . $row, $transaction->transaction_id);
            $sheet->setCellValue('B' . $row, $transaction->order->order_id ?? 'N/A');
            $sheet->setCellValue('C' . $row, $transaction->content);
            $sheet->setCellValue('D' . $row, $transaction->amount);
            $sheet->setCellValue('E' . $row, $typeText);
            $sheet->setCellValue('F' . $row, $statusText);
            $sheet->setCellValue('G' . $row, $transaction->created_at->format('d/m/Y H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'transactions-' . date('Y-m-d') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            if (ob_get_length())
                ob_end_clean();
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportRevenuePdf(int $days = 30)
    {
        $data = $this->getRevenueData($days);
        $data['days'] = $days;
        $data['date'] = now()->format('d/m/Y');

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->download('revenue-report-' . date('Y-m-d') . '.pdf');
    }
    public function getRevenueData(int $days = 30): array
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days);

        // Calculate revenue by day
        $revenueData = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->where('status', OrderStatus::DELIVERED) // Delivered/Completed
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Calculate order count by day
        $ordersData = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format for Chart.js
        $labels = [];
        $revenue = [];
        $orders = [];

        // Pre-fill all dates to avoid gaps
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d/m');

            $labels[] = $displayDate;

            $dayRevenue = $revenueData->firstWhere('date', $formattedDate);
            $revenue[] = $dayRevenue ? (float) $dayRevenue->total : 0;

            $dayOrders = $ordersData->firstWhere('date', $formattedDate);
            $orders[] = $dayOrders ? (int) $dayOrders->count : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders
        ];
    }

    public function getTopProducts(int $limit = 10, int $days = 30)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days);

        $query = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->join('products', 'products.product_id', '=', 'order_items.product_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', OrderStatus::CANCELLED);

        $query->where('orders.status', '!=', OrderStatus::PENDING_PAYMENT);

        $topProducts = $query->select(
            'products.title',
            'products.thumb',
            'products.price',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
        )
            ->groupBy('products.product_id', 'products.title', 'products.thumb', 'products.price')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        // Calculate percentage for progress bars
        $maxSold = $topProducts->max('total_sold') ?? 1;

        return $topProducts->map(function ($item) use ($maxSold) {
            $item->percentage = round(($item->total_sold / $maxSold) * 100);
            return $item;
        });
    }

    public function getCustomerAnalytics(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now();

        // 1. New Customers: Registered in this period
        $newCustomers = DB::table('users')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_admin', false)
            ->count();

        // 2. Returning Customers: Ordered in this period BUT created before this period
        // Find users who have orders in range
        $activeUserIds = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        $returningCustomers = DB::table('users')
            ->whereIn('user_id', $activeUserIds)
            ->where('created_at', '<', $startDate)
            ->count();

        return [
            'labels' => ['Khách hàng mới', 'Khách hàng quay lại'],
            'data' => [$newCustomers, $returningCustomers],
            'total' => $newCustomers + $returningCustomers
        ];
    }
}
