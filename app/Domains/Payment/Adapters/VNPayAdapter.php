<?php

namespace App\Domains\Payment\Adapters;

use App\Domains\Payment\Builders\VNPayParamsBuilder;
use App\Domains\Payment\Constants\VNPayParams;
use App\Domains\Payment\Constants\VNPayResponseCode;
use App\Domains\Payment\DTOs\Commands\InitPaymentDTO;
use App\Domains\Payment\DTOs\Commands\RefundRequestDTO;
use App\Domains\Payment\DTOs\Responses\InitPaymentResponseDTO;
use App\Domains\Payment\DTOs\Responses\RefundResponseDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VNPayAdapter implements PaymentAdapterInterface
{
    public function __construct(
        private readonly VNPayParamsBuilder $paramsBuilder,
    ) {
    }

    public function initPayment(InitPaymentDTO $dto): InitPaymentResponseDTO
    {
        $params = $this->paramsBuilder->build($dto);
        $paymentUrl = $this->buildPaymentUrl($params);

        return new InitPaymentResponseDTO(
            orderId: $dto->orderId,
            paymentUrl: $paymentUrl,
        );
    }

    public function verifyCallback(array $params): bool
    {
        $secureHash = $params[VNPayParams::SECURE_HASH] ?? '';
        unset($params[VNPayParams::SECURE_HASH]);
        unset($params[VNPayParams::SECURE_HASH_TYPE]);

        $calculatedHash = $this->buildSecureHash($params);

        return hash_equals($secureHash, $calculatedHash);
    }

    public function refund(RefundRequestDTO $dto): RefundResponseDTO
    {
        $requestId = sprintf('%s_%d', date('YmdHis'), $dto->orderId);
        $vnCalendar = Carbon::now('Asia/Ho_Chi_Minh');
        $createDate = $vnCalendar->format('YmdHis');

        $params = [
            VNPayParams::REQUEST_ID => $requestId,
            VNPayParams::VERSION => config('vnpay.version'),
            VNPayParams::COMMAND => 'refund',
            VNPayParams::TMN_CODE => config('vnpay.tmn_code'),
            VNPayParams::TRANSACTION_TYPE => '02',
            VNPayParams::TXN_REF => (string) $dto->orderId,
            VNPayParams::AMOUNT => bcmul($dto->amount, '100', 0),
            VNPayParams::TRANSACTION_NO => $dto->transactionNo,
            VNPayParams::TRANSACTION_DATE => $dto->transactionDate,
            VNPayParams::CREATE_BY => $dto->createdBy,
            VNPayParams::CREATE_DATE => $createDate,
            VNPayParams::IP_ADDRESS => $dto->ipAddress,
            VNPayParams::ORDER_INFO => $dto->orderInfo . ' #' . $dto->orderId,
        ];

        $secureHash = $this->buildRefundSecureHash($params);
        $params[VNPayParams::SECURE_HASH] = $secureHash;

        try {
            $response = Http::asJson()->post(config('vnpay.refund_url'), $params);
            $result = $response->json();

            Log::info('VNPay Refund Response', $result);

            $responseCode = $result[VNPayParams::RESPONSE_CODE] ?? '99';
            $success = $responseCode === VNPayResponseCode::SUCCESS;

            return new RefundResponseDTO(
                success: $success,
                responseCode: $responseCode,
                message: $result[VNPayParams::MESSAGE] ?? null,
                transactionNo: $result[VNPayParams::TRANSACTION_NO] ?? null,
                responseId: $result[VNPayParams::RESPONSE_ID] ?? null,
            );
        } catch (\Exception $e) {
            Log::error('VNPay Refund Error', ['error' => $e->getMessage()]);

            return new RefundResponseDTO(
                success: false,
                responseCode: '99',
                message: 'Refund request failed: ' . $e->getMessage(),
            );
        }
    }

    private function buildPaymentUrl(array $params): string
    {
        $secureHash = $this->buildSecureHash($params);
        $query = $this->buildQuery($params, $secureHash);

        return config('vnpay.pay_url') . '?' . $query;
    }

    private function buildSecureHash(array $params): string
    {
        ksort($params);

        $hashData = collect($params)
            ->map(fn($value, $key) => $key . '=' . urlencode($value))
            ->implode('&');

        return hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));
    }

    private function buildRefundSecureHash(array $params): string
    {
        $hashData = implode('|', [
            $params[VNPayParams::REQUEST_ID],
            $params[VNPayParams::VERSION],
            $params[VNPayParams::COMMAND],
            $params[VNPayParams::TMN_CODE],
            $params[VNPayParams::TRANSACTION_TYPE],
            $params[VNPayParams::TXN_REF],
            $params[VNPayParams::AMOUNT],
            $params[VNPayParams::TRANSACTION_NO],
            $params[VNPayParams::TRANSACTION_DATE],
            $params[VNPayParams::CREATE_BY],
            $params[VNPayParams::CREATE_DATE],
            $params[VNPayParams::IP_ADDRESS],
            $params[VNPayParams::ORDER_INFO],
        ]);

        return hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));
    }

    private function buildQuery(array $params, string $secureHash): string
    {
        ksort($params);

        $query = collect($params)
            ->map(fn($value, $key) => urlencode($key) . '=' . urlencode($value))
            ->implode('&');

        return $query . '&' . VNPayParams::SECURE_HASH . '=' . $secureHash;
    }
}
