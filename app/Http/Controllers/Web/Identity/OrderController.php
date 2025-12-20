<?php

namespace App\Http\Controllers\Web\Identity;

use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Payment\Constants\PaymentProvider;
use App\Domains\Payment\Services\PaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product'])->orderByDesc('created_at')->get();
        return view('pages.account.orders', compact('orders'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with(['items.product'])->where('order_id', $id)->firstOrFail();
        return view('pages.account.order-detail', compact('order'));
    }

    public function repay($id)
    {
        $userId = Auth::id();
        \Illuminate\Support\Facades\Log::info("Repay requested for Order ID: $id by User: " . ($userId ?? 'Guest'));

        if (!$userId) {
            \Illuminate\Support\Facades\Log::error('Repay: User not authenticated');
            return redirect()->route('login');
        }

        try {
            // Direct query to avoid potentially broken User relation
            $order = \App\Domains\Order\Entities\Order::where('order_id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            \Illuminate\Support\Facades\Log::info("Order matches. Status: {$order->status}, Method: {$order->payment_method}");

            // Check status (Normalized) - PENDING_PAYMENT = 1
            if ((int) $order->status !== 1) {
                \Illuminate\Support\Facades\Log::warning("Repay failed: Order $id status is {$order->status}, expected 1.");
                return redirect()->back()->with('error', 'Đơn hàng không thể thanh toán lại (Trạng thái được cập nhật).');
            }

            $method = strtolower($order->payment_method ?? '');

            if ($method === 'vnpay') {
                \Illuminate\Support\Facades\Log::info("Repay VNPAY matched. Initializing...");
                $ip = request()->ip() ?? '127.0.0.1';
                $response = $this->paymentService->initVNPayPayment($order, $ip);
                \Illuminate\Support\Facades\Log::info("VNPay Redirect URL: " . $response->paymentUrl);
                return redirect($response->paymentUrl);
            } elseif ($method === 'payos' || $method === 'banking') {
                \Illuminate\Support\Facades\Log::info("Repay PAYOS matches. Initializing...");
                $response = $this->paymentService->initPayOSPayment($order);
                \Illuminate\Support\Facades\Log::info("PayOS Redirect URL: " . $response->paymentUrl);
                return redirect($response->paymentUrl);
            } else {
                \Illuminate\Support\Facades\Log::warning("Repay Method Mismatch: $method");
                return redirect()->back()->with('error', 'Phương thức thanh toán không hỗ trợ thanh toán online.');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Repay Exception for Order $id: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
