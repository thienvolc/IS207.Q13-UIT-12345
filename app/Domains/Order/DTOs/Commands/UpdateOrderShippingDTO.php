<?php

namespace App\Domains\Order\DTOs\Commands;

readonly class UpdateOrderShippingDTO
{
    public function __construct(
        public int     $orderId,
        public ?string $firstName = null,
        public ?string $middleName = null,
        public ?string $lastName = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $city = null,
        public ?string $province = null,
        public ?string $country = null,
    ) {}

    public function toArray(): array
    {

        return array_filter([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
        ], fn($v) => !is_null($v));
    }
}
