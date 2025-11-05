<?php

namespace App\Http\Controllers\Me;

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
        return $this->successResponse($cart);
    }

    /**
     * POST /me/carts/items
     */
    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $item = $this->cartService->addItem($request->validated());
        return $this->successResponse($item);
    }

    /**
     * PATCH /me/carts/items/{cart_item_id}
     */
    public function updateItem(UpdateCartItemRequest $request, int $cart_item_id): JsonResponse
    {
        $item = $this->cartService->updateItem($cart_item_id, $request->validated());
        return $this->successResponse($item);
    }

    /**
     * DELETE /me/carts/items/{cart_item_id}
     */
    public function deleteItem(int $cart_item_id): JsonResponse
    {
        $item = $this->cartService->deleteItem($cart_item_id);
        return $this->successResponse($item);
    }

    /**
     * DELETE /me/carts/clear
     */
    public function clearCart(): JsonResponse
    {
        $cart = $this->cartService->clearCart();
        return $this->successResponse($cart);
    }

    /**
     * PATCH /me/carts/checkout
     */
    public function checkout(CheckoutCartRequest $request): JsonResponse
    {
        $result = $this->cartService->checkout($request->validated());
        return $this->successResponse($result);
    }
}
