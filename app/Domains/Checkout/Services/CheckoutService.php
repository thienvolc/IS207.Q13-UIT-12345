<?php

namespace App\Domains\Checkout\Services;

use App\Domains\Cart\Repositories\CartItemRepository;
use App\Domains\Cart\Repositories\CartRepository;
use App\Domains\Checkout\DTOs\Commands\CheckoutCartDTO;
use App\Domains\Checkout\DTOs\Responses\CartCheckoutDTO;
use App\Domains\Checkout\Mappers\CheckoutMapper;
use App\Domains\Inventory\Services\ProductAvailabilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CheckoutService
{
    public function __construct(
        private ProductAvailabilityService $productAvailabilityService,
        private CartRepository             $cartRepository,
        private CartItemRepository         $cartItemRepository,
        private CheckoutMapper             $checkoutMapper,
    ) {}

    public function checkout(CheckoutCartDTO $dto): CartCheckoutDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $cart = $this->cartRepository->getActiveCartForUserOrFail($userId);
            $cartItems = $this->cartItemRepository->listByCartIdAndItemIds($cart->cart_id, $dto->items);

            $this->productAvailabilityService->lockStockAndValidateAvailability($cart->items);

            // TODO: reserve stock for place order

            $checkoutCart = $this->cartRepository->createCheckoutCartForUser($userId, $cartItems);
            $shippingData = $dto->getShippingData();
            $checkoutCart->update($shippingData);

            return $this->checkoutMapper->toDTO($checkoutCart);
        });
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
