<?php

namespace App\Http\Controllers\Api\Public\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Checkout\DTOs\FormRequests\CheckoutCartRequest;
use App\Domains\Checkout\Services\CheckoutService;
use App\Http\Controllers\AppController;

class CheckoutController extends AppController
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {}

    /**
     * [POST] /me/carts/checkout
     */
    public function checkout(CheckoutCartRequest $req): ResponseDTO
    {
        $result = $this->checkoutService->checkout($req->toDTO());
        return $this->success($result);
    }
}
