<?php

namespace App\Services;

use App\Constants\CartStatus;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Dtos\Cart\AddCartItemDto;
use App\Dtos\Cart\CheckoutCartDto;
use App\Dtos\Cart\UpdateCartItemDto;
use App\Exceptions\BusinessException;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\CartItemRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Collection;
use Throwable as ThrowableAlias;

class CartService
{
    public function __construct(
        private readonly CartRepository     $cartRepository,
        private readonly CartItemRepository $cartItemRepository,
        private readonly ProductRepository  $productRepository
    ) {}

    public function getOrCreateCart(): array
    {
        $userId = $this->getCurrentUserId();
        $cart = $this->cartRepository->findOrCreateActive($userId);

        return CartResource::transform($cart);
    }

    public function addItem(AddCartItemDto $dto): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->findOrCreateActive($userId);
            $product = $this->findAndLockAndValidateProduct($dto->productId);
            $cartItem = $this->updateOrCreateCartItem($cart, $product, $dto);

            return [
                'item_id' => $cartItem->cart_item_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
            ];
        });
    }

    public function updateItem(UpdateCartItemDto $dto): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cartItem = $this->findInUserCartOrFail($userId, $dto->cartItemId);
            $product = $this->findAndLockAndValidateProduct($cartItem->product_id);

            $this->assertStockAvailable($product, $dto->quantity);

            $this->updateCartItemWithCurrentPrice($cartItem, $product, $dto->quantity);

            return CartItemResource::transform($cartItem);
        });
    }

    public function deleteItem(int $cartItemId): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->findInUserCartOrFail($userId, $cartItemId);
            $replicateItem = $this->replicateCartItemAndLoadProduct($cartItem);

            $cartItem->delete();

            return CartItemResource::transform($replicateItem);
        });
    }

    public function clear(): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId) {
            $cart = $this->findActiveCartWithItemsOrFail($userId);

            $this->cartItemRepository->deleteByCartId($cart->cart_id);

            return CartResource::transform($cart);
        });
    }

    public function checkout(CheckoutCartDto $dto): array
    {
        $userId = $this->getCurrentUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->findAndLockActiveCartOrFail($userId);
            $cartItems = $this->findAndLockCartItems($cart, $dto->items);

            $this->validateAllProductsAvailable($cartItems);

            $checkoutCart = $this->cartRepository->createCheckoutCart($userId);
            $checkoutCartItems = $this->copyItemsFromCart($cartItems, $checkoutCart->cart_id);

            $this->updateCartWithShippingInfo($checkoutCart, $dto);

            return $this->buildCheckoutResponse($checkoutCart, $checkoutCartItems);
        });
    }

    private function getCurrentUserId(): int
    {
        return Auth::id();
    }

    private function findAndLockAndValidateProduct(int $productId): Product
    {
        $product = $this->productRepository->findAndLock($productId);
        $this->assertProductIsAvailable($product);

        return $product;
    }

    private function assertProductIsAvailable(?Product $product): void
    {
        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        if ($product->status !== ProductStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Product is not available'
            ]);
        }
    }

    private function assertStockAvailable(Product $product, int $requestedQuantity): void
    {
        if ($product->quantity < $requestedQuantity) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Not enough product in stock',
                'available' => $product->quantity,
                'requested' => $requestedQuantity,
            ]);
        }
    }

    private function updateOrCreateCartItem(Cart $cart, Product $product, AddCartItemDto $dto): CartItem
    {
        $existingItem = $this->cartItemRepository->findByCartAndProduct($cart->cart_id, $dto->productId);

        $inCartQuantity = $existingItem->quantity ?? 0;
        $newQuantity = $dto->quantity + $inCartQuantity;

        $this->assertSufficientStock($product, $newQuantity, $inCartQuantity);

        return $existingItem
            ? $this->updateCartItem($existingItem, $product, $newQuantity, $dto->note)
            : $this->createCartItem($cart, $product, $dto);
    }

    private function assertSufficientStock(Product $product, int $totalQuantity, int $inCartQuantity): void
    {
        if ($product->quantity < $totalQuantity) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Not enough product in stock',
                'available' => $product->quantity,
                'in_cart' => $inCartQuantity,
                'requested' => $totalQuantity - $inCartQuantity,
                'total_needed' => $totalQuantity,
            ]);
        }
    }

    private function updateCartItem(
        CartItem $cartItem,
        Product  $product,
        int      $quantity,
        ?string  $note
    ): CartItem
    {
        $cartItem->update([
            'quantity' => $quantity,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
            'note' => $note ?? $cartItem->note,
        ]);

        return $cartItem;
    }

    private function createCartItem(Cart $cart, Product $product, AddCartItemDto $dto): CartItem
    {
        $cartItem = $this->cartItemRepository->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
            'quantity' => $dto->quantity,
            'note' => $dto->note,
        ]);

        $cartItem->load('product');
        return $cartItem;
    }

    private function findInUserCartOrFail(int $userId, int $cartItemId)
    {
        $cartItem = $this->cartItemRepository->findInUserCart($userId, $cartItemId);

        if (!$cartItem) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $cartItem;
    }

    private function updateCartItemWithCurrentPrice(
        CartItem $cartItem,
        Product  $product,
        int      $quantity
    ): void
    {
        $cartItem->update([
            'quantity' => $quantity,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
        ]);
        $cartItem->load('product');
    }

    private function replicateCartItemAndLoadProduct(CartItem $cartItem): CartItem
    {
        $replicate = $cartItem->replicate();
        $replicate->load('product');
        return $replicate;
    }

    private function findActiveCartWithItemsOrFail(int $userId)
    {
        $cart = $this->cartRepository->findActive($userId);

        if (!$cart) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $cart->load('items');

        return $cart;
    }

    private function findAndLockActiveCartOrFail(int $userId): Cart
    {
        $cart = $this->cartRepository->findAndLockActive($userId);

        if (!$cart) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $cart;
    }

    private function findAndLockCartItems($cart, array $itemIds): Collection
    {
        return $this->cartItemRepository->findAndLockByIds($cart->cart_id, $itemIds);
    }

    private function copyItemsFromCart(Collection $cartItems, $cartId): Collection
    {
        $cartItems = $cartItems->map(function (CartItem $item) use ($cartId) {
            $replicate = $item->replicate();
            $replicate->cart_id = $cartId;

            return $replicate;
        });

        $this->cartItemRepository->insert($cartItems->toArray());

        return $cartItems;
    }

    private function validateAllProductsAvailable($cartItems): void
    {
        foreach ($cartItems as $item) {
            if (!$item->product) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => "Product {$item->product_id} not found"
                ]);
            }

            if ($item->product->status !== ProductStatus::ACTIVE) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => "Product {$item->product->title} is not available"
                ]);
            }

            if ($item->product->quantity < $item->quantity) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => "Not enough stock for {$item->product->title}",
                    'available' => $item->product->quantity,
                    'requested' => $item->quantity,
                ]);
            }
        }
    }

    private function updateCartWithShippingInfo(Cart $cart, CheckoutCartDto $dto): void
    {
        $shippingData = $dto->getShippingData();
        $shippingData['status'] = CartStatus::CHECKED_OUT;
        $cart->update($shippingData);
    }

    private function buildCheckoutResponse(Cart $cart, $cartItems): array
    {
        return [
            'cart_id' => $cart->cart_id,
            'item_count' => $cartItems->count(),
            'shipping_address' => [
                'line1' => $cart->line1,
                'line2' => $cart->line2,
                'city' => $cart->city,
                'province' => $cart->province,
                'country' => $cart->country,
            ],
            'items' => CartItemResource::collection($cartItems),
        ];
    }
}
