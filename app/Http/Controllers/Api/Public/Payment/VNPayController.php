<?php

namespace App\Http\Controllers\Api\Public\Payment;

use App\Domains\Payment\Constants\VNPayResponseCode;
use App\Domains\Payment\Services\VNPayIpnHandler;
use App\Http\Controllers\AppController;
use Illuminate\Http\Request;

class VNPayController extends AppController
{
    public function __construct(
        private readonly VNPayIpnHandler $ipnHandler
    ) {
    }

    // [GET] /payment/vnpay/ipn - VNPay IPN callback
    public function ipn(Request $request)
    {
        $params = $request->all();
        $response = $this->ipnHandler->process($params);

        return response()->json($response);
    }

    // [GET] /payment/vnpay/return - Return URL after payment (redirect to frontend)
    public function return(Request $request)
    {
        $orderId = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
        $status = $responseCode === VNPayResponseCode::SUCCESS ? 'success' : 'failed';

        return redirect()->to("{$frontendUrl}/checkout/result?order_id={$orderId}&status={$status}");
    }
}

