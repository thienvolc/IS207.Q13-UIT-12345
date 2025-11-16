<?php

namespace App\Domains\Sales\DTOs\Cart\Requests;

readonly class CheckoutCartDTO
{
    public function __construct(
        public array   $items,
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
        public ?string $note,
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
