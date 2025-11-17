<?php

namespace App\Domains\Sales\Services;

use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Sales\DTOs\Cart\Requests\AddCartItemDTO;
use App\Domains\Sales\DTOs\Cart\Requests\UpdateCartItemDTO;
use App\Domains\Sales\DTOs\Cart\Responses\CartItemResponseDTO;
use App\Domains\Sales\DTOs\Cart\Responses\CartResponseDTO;
use App\Domains\Sales\Entities\Cart;
use App\Domains\Sales\Entities\CartItem;
use App\Domains\Sales\Repositories\CartItemRepository;
use App\Domains\Sales\Repositories\CartRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CartService
{
    public function __construct(
        private ProductAvailabilityService $productAvailabilityService,
        private CartRepository             $cartRepository,
        private CartItemRepository         $cartItemRepository,
        private ProductRepository          $productRepository
    ) {}

    public function getOrCreateActiveCart(): CartResponseDTO
    {
        $cart = $this->cartRepository->getActiveOrCreateForUser($this->userId());
        return CartResponseDTO::fromModel($cart);
    }

    public function addOrIncrementQuantityCartItem(AddCartItemDTO $dto): CartItemResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->getActiveOrCreateForUser($userId);
            $product = $this->productRepository->getActiveByIdOrFail($dto->productId);

            $this->productAvailabilityService->assertStockAvailable($product, $dto->quantity);

            $cartItem = $this->createOrIncrementQuantityCartItem($cart, $product, $dto);

            return CartItemResponseDTO::fromModel($cartItem);
        });
    }

    public function removeItem(int $cartItemId): CartItemResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->cartItemRepository->getByIdAndUserOrFail($cartItemId, $userId);
            $replica = $cartItem->replicate();

            $cartItem->delete();

            return CartItemResponseDTO::fromModel($replica);
        });
    }

    public function clear(): CartResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId) {
            $emptyCart = $this->cartRepository->clearCartForUser($userId);

            return CartResponseDTO::fromModel($emptyCart);
        });
    }

    private function createOrIncrementQuantityCartItem(Cart $cart, Product $product, AddCartItemDTO $dto): CartItem
    {
        $existingItem = $this->cartItemRepository->findOneByCartIdAndProductId($cart->cart_id, $dto->productId);

        if ($existingItem) {
            $totalQuantity = $dto->quantity + $existingItem->quantity;
            $this->productAvailabilityService->assertSufficientStock(
                $product,
                $totalQuantity,
                $existingItem->quantity
            );

            return $this->incrementQuantityCartItem($existingItem, $product, $totalQuantity);
        }

        return $this->createCartItem($cart, $product, $dto);
    }

    private function incrementQuantityCartItem(CartItem $cartItem, Product $product, int $quantity): CartItem
    {
        $cartItem->update([
            'quantity'  => $quantity,
            'price'     => $product->price,
            'discount'  => $product->discount ?? 0,
        ]);

        return $cartItem;
    }

    private function createCartItem(Cart $cart, Product $product, AddCartItemDTO $dto): CartItem
    {
        return $this->cartItemRepository->create([
            'cart_id'   => $cart->cart_id,
            'product_id'=> $product->product_id,
            'price'     => $product->price,
            'discount'  => $product->discount ?? 0,
            'quantity'  => $dto->quantity
        ]);
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
