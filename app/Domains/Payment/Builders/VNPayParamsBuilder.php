<?php

namespace App\Domains\Payment\Builders;

use App\Domains\Payment\Constants\VNPayParams;
use App\Domains\Payment\DTOs\Commands\InitPaymentDTO;
use Carbon\Carbon;

class VNPayParamsBuilder
{
    public function build(InitPaymentDTO $dto): array
    {
        $vnCalendar = Carbon::now('Asia/Ho_Chi_Minh');
        $createDate = $vnCalendar->format('YmdHis');
        $expireDate = $vnCalendar->addMinutes((int) config('vnpay.payment_timeout'))->format('YmdHis');

        $amount = bcmul($dto->amount, '100', 0);

        return [
            VNPayParams::VERSION => config('vnpay.version'),
            VNPayParams::COMMAND => config('vnpay.command'),
            VNPayParams::TMN_CODE => config('vnpay.tmn_code'),
            VNPayParams::AMOUNT => $amount,
            VNPayParams::CURRENCY_CODE => config('vnpay.currency'),
            VNPayParams::TXN_REF => $dto->txnRef ?? (string) $dto->orderId,
            VNPayParams::RETURN_URL => config('vnpay.return_url'),
            VNPayParams::ORDER_TYPE => config('vnpay.order_type'),
            VNPayParams::ORDER_INFO => $dto->orderInfo,
            VNPayParams::CREATE_DATE => $createDate,
            VNPayParams::EXPIRE_DATE => $expireDate,
            VNPayParams::LOCALE => config('vnpay.locale'),
            VNPayParams::IP_ADDRESS => $dto->ipAddress,
        ];
    }
}
