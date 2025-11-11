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

readonly class CartService
{
    public function __construct(
        private CartRepository     $cartRepository,
        private CartItemRepository $cartItemRepository,
        private ProductRepository  $productRepository
    ) {}

    public function getOrCreateCart(): array
    {
        $userId = $this->getAuthUserId();
        $cart = $this->cartRepository->findOrCreateActiveCart($userId);

        return CartResource::transform($cart);
    }

    public function addItem(AddCartItemDto $addCartItemDto): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $addCartItemDto) {
            $cart = $this->cartRepository->findOrCreateActiveCart($userId);
            $product = $this->productRepository->findAndLock($addCartItemDto->productId);

            $this->assertProductIsAvailable($product);

            $existingCartItem = $this->cartItemRepository->findByCartAndProduct(
                $cart->cart_id, $addCartItemDto->productId);

            $inCartQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
            $newQuantity = $addCartItemDto->quantity + $inCartQuantity;

            $this->assertSufficientStock($product, $newQuantity, $inCartQuantity);

            if ($existingCartItem) {
                $this->updateExistingCartItem($existingCartItem,
                    $product, $newQuantity, $addCartItemDto->note);
            } else {
                $existingCartItem = $this->createNewCartItem($cart, $product, $addCartItemDto);
            }

            return [
                'item_id' => $existingCartItem->cart_item_id,
                'product_id' => $existingCartItem->product_id,
                'quantity' => $existingCartItem->quantity,
            ];
        });
    }

    public function updateItem(UpdateCartItemDto $dto): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cartItem = $this->cartItemRepository->findUserCartItem($userId, $dto->cartItemId);

            if (!$cartItem) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $product = $this->productRepository->findAndLock($cartItem->product_id);

            $this->assertProductIsAvailable($product);
            $this->assertStockAvailable($product, $dto->quantity);

            $this->updateCartItemWithCurrentPrice($cartItem, $product, $dto->quantity);

            return CartItemResource::transform($cartItem);
        });
    }

    public function deleteItem(int $cartItemId): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->cartItemRepository->findUserCartItem($userId, $cartItemId);

            if (!$cartItem) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $deletedItem = $this->replicateCartItemWithProduct($cartItem);
            $cartItem->delete();

            return CartItemResource::transform($deletedItem);
        });
    }

    public function clearCart(): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId) {
            $cart = $this->cartRepository->findActiveCart($userId);

            if (!$cart) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $this->cartItemRepository->deleteByCartId($cart->cart_id);
            $cart->load('items');

            return CartResource::transform($cart);
        });
    }

    public function checkout(CheckoutCartDto $dto): array
    {
        $userId = $this->getAuthUserId();

        return DB::transaction(function () use ($userId, $dto) {
            $cartItems = $this->findAndLockItemsForActiveCart($userId, $dto->items);
            $this->validateAllProductsAvailable($cartItems);

            $checkoutCart = $this->cartRepository->createCheckoutCart($userId);
            $checkoutCartItems = $this->replicateCartItemsForCart($cartItems, $checkoutCart->cart_id);

            $this->updateCartWithShippingInfo($checkoutCart, $dto);

            return $this->buildCheckoutResponse($checkoutCart, $checkoutCartItems);
        });
    }

    private function getAuthUserId(): int
    {
        return Auth::id();
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

    private function assertSufficientStock(
        Product $product,
        int     $totalQuantity,
        int     $inCartQuantity
    ): void
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

    private function updateExistingCartItem(
        CartItem $cartItem,
        Product  $product,
        int      $quantity,
        ?string  $note
    ): void
    {
        $cartItem->update([
            'quantity' => $quantity,
            'price' => $product->price,
            'discount' => $product->discount ?? 0,
            'note' => $note ?? $cartItem->note,
        ]);
    }

    private function createNewCartItem(Cart $cart, Product $product, AddCartItemDto $dto): CartItem
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

    private function replicateCartItemWithProduct(CartItem $cartItem): CartItem
    {
        $deletedItem = $cartItem->replicate();
        $deletedItem->load('product');
        return $deletedItem;
    }

    private function findAndLockItemsForActiveCart(int $userId, array $itemIds): Collection
    {
        $cart = $this->cartRepository->findAndLockActiveCart($userId);
        if (!$cart) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $this->cartItemRepository->findAndLockByIds($cart->cart_id, $itemIds);
    }

    private function replicateCartItemsForCart(Collection $cartItems, int $cart_id): Collection
    {
        return $cartItems->map(function (CartItem $item) use ($cart_id) {
            $clone = $item->replicate();
            $clone->cart_id = $cart_id;
            $clone->save();

            return $clone;
        });
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
