<?php

namespace App\Dtos\Cart;

class CheckoutCartDto
{
    public function __construct(
        public readonly array $items,
        public readonly string $firstName,
        public readonly ?string $middleName,
        public readonly string $lastName,
        public readonly string $phone,
        public readonly string $email,
        public readonly string $line1,
        public readonly ?string $line2,
        public readonly string $city,
        public readonly string $province,
        public readonly string $country,
        public readonly ?string $note,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            items: $data['items'],
            firstName: $data['first_name'],
            middleName: $data['middle_name'] ?? null,
            lastName: $data['last_name'],
            phone: $data['phone'],
            email: $data['email'],
            line1: $data['line1'],
            line2: $data['line2'] ?? null,
            city: $data['city'],
            province: $data['province'],
            country: $data['country'],
            note: $data['note'] ?? null,
        );
    }

    public function getShippingData(): array
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
            'note' => $this->note,
        ];
    }
}
