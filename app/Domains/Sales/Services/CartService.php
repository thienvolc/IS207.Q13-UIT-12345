<?php

namespace App\Domains\Sales\Services;

use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Sales\DTOs\Cart\Requests\AddCartItemDTO;
use App\Domains\Sales\DTOs\Cart\Requests\UpdateCartItemDTO;
use App\Domains\Sales\DTOs\Cart\Responses\AddItemResponseDTO;
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

    public
    function getOrCreateCart(): CartResponseDTO
    {
        $cart = $this->cartRepository->findOrCreateActiveByUserId($this->userId());
        return CartResponseDTO::fromModel($cart);
    }

    public
    function addItem(AddCartItemDTO $dto): AddItemResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->findOrCreateActiveByUserId($userId);
            $product = $this->productRepository->findActiveOrFail($dto->productId);
            $cartItem = $this->updateQuantityOrCreateCartItem($cart, $product, $dto);

            return AddItemResponseDTO::fromModel($cartItem);
        });
    }

    public
    function updateItem(UpdateCartItemDTO $dto): CartItemResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cartItem = $this->cartItemRepository->findInUserCartOrFail($userId, $dto->cartItemId);
            $product = $this->productRepository->findActiveOrFail($cartItem->product_id);

            $this->productAvailabilityService->assertStockAvailable($product, $dto->quantity);

            $cartItem = $this->updateCartItem($cartItem, $product, $dto->quantity);

            return CartItemResponseDTO::fromModel($cartItem);
        });
    }

    public
    function deleteItem(int $cartItemId): CartItemResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->cartItemRepository->findInUserCartOrFail($userId, $cartItemId);

            $replicate = $cartItem->replicate();
            $replicate->load('product');

            $cartItem->delete();

            return CartItemResponseDTO::fromModel($replicate);
        });
    }

    public
    function clear(): CartResponseDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId) {
            $cart = $this->cartRepository->clearCartByUserId($userId);

            return CartResponseDTO::fromModel($cart);
        });
    }

    private
    function userId(): int
    {
        return Auth::id();
    }

    private
    function updateQuantityOrCreateCartItem(Cart $cart, Product $product, AddCartItemDTO $dto): CartItem
    {
        $existingItem = $this->cartItemRepository->findByCartIdAndProductId($cart->cart_id, $dto->productId);

        $inCartQuantity = $existingItem->quantity ?? 0;
        $newQuantity = $dto->quantity + $inCartQuantity;

        $this->productAvailabilityService->assertSufficientStock($product, $newQuantity, $inCartQuantity);

        return $existingItem
            ? $this->updateCartItem($existingItem, $product, $newQuantity)
            : $this->createCartItem($cart, $product, $dto);
    }

    private
    function updateCartItem(CartItem $cartItem, Product $product, int $quantity): CartItem
    {
        $cartItem->update([
            'quantity' => $quantity,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
        ]);
        $cartItem->load('product');

        return $cartItem;
    }

    private
    function createCartItem(Cart $cart, Product $product, AddCartItemDTO $dto): CartItem
    {
        $cartItem = $this->cartItemRepository->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
            'quantity' => $dto->quantity
        ]);
        $cartItem->load('product');

        return $cartItem;
    }
}
