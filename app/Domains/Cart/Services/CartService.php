<?php

namespace App\Domains\Cart\Services;

use App\Domains\Cart\DTOs\Commands\AddCartItemDTO;
use App\Domains\Cart\DTOs\Responses\CartDTO;
use App\Domains\Cart\DTOs\Responses\CartItemDTO;
use App\Domains\Cart\Entities\Cart;
use App\Domains\Cart\Entities\CartItem;
use App\Domains\Cart\Mappers\CartMapper;
use App\Domains\Cart\Repositories\CartItemRepository;
use App\Domains\Cart\Repositories\CartRepository;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Inventory\Services\ProductAvailabilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CartService
{
    public function __construct(
        private ProductAvailabilityService $productAvailabilityService,
        private CartRepository             $cartRepository,
        private CartItemRepository         $cartItemRepository,
        private ProductRepository          $productRepository,
        private CartMapper                 $cartMapper
    ) {}

    public function getOrCreateActiveCart(): CartDTO
    {
        $cart = $this->cartRepository->getActiveOrCreateForUser($this->userId());
        return $this->cartMapper->toDTO($cart);
    }

    public function addOrIncrementQuantityCartItem(AddCartItemDTO $dto): CartItemDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->getActiveOrCreateForUser($userId);
            $product = $this->productRepository->getActiveByIdOrFail($dto->productId);

            $this->productAvailabilityService->assertStockAvailable($product, $dto->quantity);

            $cartItem = $this->createOrIncrementQuantityCartItem($cart, $product, $dto);

            return $this->cartMapper->toItemDTO($cartItem);
        });
    }

    public function removeItem(int $cartItemId): CartItemDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->cartItemRepository->getByIdAndUserOrFail($cartItemId, $userId);

            $itemDTO = $this->cartMapper->toItemDTO($cartItem);

            $cartItem->delete();

            return $itemDTO;
        });
    }

    public function clear(): CartDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId) {
            $emptyCart = $this->cartRepository->clearCartForUser($userId);

            return $this->cartMapper->toDTO($emptyCart);
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
