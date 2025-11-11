<?php

namespace App\Http\Controllers\Me;

use App\Dtos\Cart\AddCartItemDto;
use App\Dtos\Cart\CheckoutCartDto;
use App\Dtos\Cart\UpdateCartItemDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Requests\Cart\CheckoutCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends AppController
{
    public function __construct(
        private CartService $cartService
    ) {}

    /**
     * GET /me/carts
     */
    public function index(): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart();
        return $this->success($cart);
    }

    /**
     * POST /me/carts/items
     */
    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $dto = AddCartItemDto::fromArray($request->validated());
        $item = $this->cartService->addItem($dto);
        return $this->success($item);
    }

    /**
     * PATCH /me/carts/items/{cart_item_id}
     */
    public function updateItem(UpdateCartItemRequest $request, int $cart_item_id): JsonResponse
    {
        $dto = UpdateCartItemDto::fromArray($request->validated(), $cart_item_id);
        $item = $this->cartService->updateItem($dto);
        return $this->success($item);
    }

    /**
     * DELETE /me/carts/items/{cart_item_id}
     */
    public function deleteItem(int $cart_item_id): JsonResponse
    {
        $item = $this->cartService->deleteItem($cart_item_id);
        return $this->success($item);
    }

    /**
     * DELETE /me/carts/clear
     */
    public function clearCart(): JsonResponse
    {
        $cart = $this->cartService->clearCart();
        return $this->success($cart);
    }

    /**
     * PATCH /me/carts/checkout
     */
    public function checkout(CheckoutCartRequest $request): JsonResponse
    {
        $dto = CheckoutCartDto::fromArray($request->validated());
        $result = $this->cartService->checkout($dto);
        return $this->success($result);
    }
}
