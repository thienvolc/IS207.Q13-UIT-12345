<?php

namespace App\Http\Controllers\Api\Public\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Cart\DTOs\FormRequest\AddCartItemRequest;
use App\Domains\Cart\Services\CartService;
use App\Http\Controllers\AppController;

class CartController extends AppController
{
    public function __construct(
        private readonly CartService $cartService,
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
     * [PUT] /me/carts/items/{cart_item_id}
     */
    public function updateQuantity(int $cart_item_id): ResponseDTO
    {
        $quantity = request()->validate(['quantity' => 'required|integer|min:1|max:9999'])['quantity'];
        $item = $this->cartService->updateQuantity($cart_item_id, (int)$quantity);
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
}
