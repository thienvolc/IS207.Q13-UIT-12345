<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Sales\DTOs\Cart\FormRequest\AddCartItemRequest;
use App\Domains\Sales\DTOs\Cart\FormRequest\CheckoutCartRequest;
use App\Domains\Sales\DTOs\Cart\FormRequest\UpdateCartItemRequest;
use App\Domains\Sales\DTOs\Cart\Requests\AddCartItemDTO;
use App\Domains\Sales\DTOs\Cart\Requests\CheckoutCartDTO;
use App\Domains\Sales\DTOs\Cart\Requests\UpdateCartItemDTO;
use App\Domains\Sales\Services\CartService;
use App\Domains\Sales\Services\CheckoutService;
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
    public function addItem(AddCartItemRequest $request): ResponseDTO
    {
        $dto = AddCartItemDTO::fromArray($request->validated());
        $item = $this->cartService->addOrIncrementQuantityCartItem($dto);
        return $this->success($item);
    }

    /**
     * [PATCH] /me/carts/items/{cart_item_id}
     */
    public function updateItem(UpdateCartItemRequest $request, int $cart_item_id): ResponseDTO
    {
        $dto = UpdateCartItemDTO::fromArray($request->validated(), $cart_item_id);
        $item = $this->cartService->updateItem($dto);
        return $this->success($item);
    }

    /**
     * [DELETE] /me/carts/items/{cart_item_id}
     */
    public function deleteItem(int $cart_item_id): ResponseDTO
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
    public function checkout(CheckoutCartRequest $request): ResponseDTO
    {
        $dto = CheckoutCartDTO::fromArray($request->validated());
        $result = $this->checkoutService->checkout($dto);
        return $this->success($result);
    }
}
