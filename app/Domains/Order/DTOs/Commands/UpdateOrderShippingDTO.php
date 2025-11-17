<?php

namespace App\Domains\Order\DTOs\Commands;

readonly class UpdateOrderShippingDTO
{
    public function __construct(
        public int     $orderId,
        public string  $firstName,
        public ?string $middleName,
        public string  $lastName,
        public string  $phone,
        public string  $email,
        public string  $line1,
        public ?string $line2,
        public string  $city,
        public string  $province,
        public string  $country,
    ) {}

    public function toArray(): array
    {
        return [
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
        ];
    }
}
