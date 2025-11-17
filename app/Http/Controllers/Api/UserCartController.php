<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Cart\DTOs\FormRequest\AddCartItemRequest;
use App\Domains\Cart\Services\CartService;
use App\Domains\Checkout\DTOs\Commands\CheckoutCartDTO;
use App\Domains\Checkout\DTOs\FormRequests\CheckoutCartRequest;
use App\Domains\Checkout\Services\CheckoutService;
use App\Http\Controllers\AppController;

class UserCartController extends AppController
{
    public function __construct(
        private readonly CartService     $cartService,
        private readonly CheckoutService $checkoutService
    ) {}

    /**
     * [GET] /me/carts
     */
    public function index(): ResponseDTO
    {
        $cart = $this->cartService->getOrCreateActiveCart();
        return $this->success($cart);
    }

    /**
     * [POST] /me/carts/items
     */
    public function addItem(AddCartItemRequest $req): ResponseDTO
    {
        $item = $this->cartService->addOrIncrementQuantityCartItem($req->toDTO());
        return $this->success($item);
    }

    /**
     * [DELETE] /me/carts/items/{cart_item_id}
     */
    public function removeItem(int $cart_item_id): ResponseDTO
    {
        $item = $this->cartService->removeItem($cart_item_id);
        return $this->success($item);
    }

    /**
     * [DELETE] /me/carts/clear
     */
    public function clearCart(): ResponseDTO
    {
        $cart = $this->cartService->clear();
        return $this->success($cart);
    }

    /**
     * [PATCH] /me/carts/checkout
     */
    public function checkout(CheckoutCartRequest $req): ResponseDTO
    {
        $result = $this->checkoutService->checkout($req->toDTO());
        return $this->success($result);
    }
}
