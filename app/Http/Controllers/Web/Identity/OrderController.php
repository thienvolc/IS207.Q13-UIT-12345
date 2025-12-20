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
        \Illuminate\Support\Facades\Log::info("Repay requested for Order ID: $id" . " by User: " . (Auth::id() ?? 'Guest'));

        $user = Auth::user();

        if (!$user) {
            \Illuminate\Support\Facades\Log::error('Repay: User not authenticated');
            return redirect()->route('login');
        }

        try {
            $order = $user->orders()->where('order_id', $id)->firstOrFail();

            \Illuminate\Support\Facades\Log::info("Order retrieved. Status: {$order->status}, Payment Method: {$order->payment_method}");

            if ($order->status !== \App\Domains\Order\Constants\OrderStatus::PENDING_PAYMENT) {
                \Illuminate\Support\Facades\Log::warning("Repay failed: Order $id not pending.");
                return redirect()->back()->with('error', 'Đơn hàng không thể thanh toán lại.');
            }

            if ($order->payment_method === \App\Domains\Payment\Constants\PaymentProvider::VNPAY) {
                \Illuminate\Support\Facades\Log::info("Repay VNPay for Order $id");
                $response = $this->paymentService->initVNPayPayment($order, request()->ip());
                return redirect($response->paymentUrl);
            } elseif (
                $order->payment_method === \App\Domains\Payment\Constants\PaymentProvider::PAYOS
                || $order->payment_method === \App\Domains\Payment\Constants\PaymentProvider::BANKING
            ) {
                \Illuminate\Support\Facades\Log::info("Repay PayOS (Banking) for Order $id");
                $response = $this->paymentService->initPayOSPayment($order);
                \Illuminate\Support\Facades\Log::info("PayOS Redirect URL: " . $response->paymentUrl);
                return redirect($response->paymentUrl);
            } else {
                return redirect()->back()->with('error', 'Phương thức thanh toán không hỗ trợ thanh toán online.');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Repay Exception for Order $id: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
