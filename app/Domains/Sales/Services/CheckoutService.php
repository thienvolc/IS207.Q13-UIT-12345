<?php

namespace App\Domains\Sales\Services;

use App\Domains\Sales\DTOs\Cart\Requests\CheckoutCartDTO;
use App\Domains\Sales\DTOs\Cart\Responses\CheckoutResponseDTO;
use App\Domains\Sales\Repositories\CartItemRepository;
use App\Domains\Sales\Repositories\CartRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CheckoutService
{

    public function __construct(
        private ProductAvailabilityService $productAvailabilityService,
        private CartRepository             $cartRepository,
        private CartItemRepository         $cartItemRepository,
    ) {}

    public function checkout(CheckoutCartDTO $dto): CheckoutResponseDTO
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

            return CheckoutResponseDTO::fromModel($checkoutCart);
        });
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
