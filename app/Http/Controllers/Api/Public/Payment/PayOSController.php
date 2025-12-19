<?php

namespace App\Http\Controllers\Api\Public\Payment;

use App\Domains\Payment\Services\PayOSWebhookHandler;
use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PayOSController extends AppController
{
    public function __construct(
        private readonly PayOSWebhookHandler $webhookHandler
    ) {
    }

    // [POST] /payment/payos/webhook - PayOS webhook callback
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        $response = $this->webhookHandler->process($payload);

        return response()->json($response);
    }

    // [GET] /payment/payos/return - Return URL after payment
    public function return(Request $request)
    {
        $orderCode = $request->get('orderCode');
        $status = $request->get('status');

        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
        $resultStatus = $status === 'PAID' ? 'success' : 'failed';

        return redirect()->to("{$frontendUrl}/checkout/result?order_id={$orderCode}&status={$resultStatus}");
    }

    // [GET] /payment/payos/cancel - Cancel URL
    public function cancel(Request $request)
    {
        $orderCode = $request->get('orderCode');

        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        return redirect()->to("{$frontendUrl}/checkout/result?order_id={$orderCode}&status=cancelled");
    }
}
