<?php

namespace App\Dtos\Product;

readonly class UpdateProductDto
{
    public function __construct(
        public int $productId,
        public ?string $title,
        public ?string $desc,
        public ?string $summary,
        public ?string $slug,
        public ?string $thumbnail,
        public ?float $price,
        public ?float $salePrice,
        public ?int $quantity,
        public ?int $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            title: $data['title'] ?? null,
            desc: $data['desc'] ?? null,
            summary: $data['summary'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            price: $data['price'] ?? null,
            salePrice: $data['sale_price'] ?? null,
            quantity: $data['quantity'] ?? null,
            status: $data['status'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->title !== null) $data['title'] = $this->title;
        if ($this->desc !== null) $data['desc'] = $this->desc;
        if ($this->summary !== null) $data['summary'] = $this->summary;
        if ($this->slug !== null) $data['slug'] = $this->slug;
        if ($this->thumbnail !== null) $data['thumbnail'] = $this->thumbnail;
        if ($this->price !== null) $data['price'] = $this->price;
        if ($this->salePrice !== null) $data['sale_price'] = $this->salePrice;
        if ($this->quantity !== null) $data['quantity'] = $this->quantity;
        if ($this->status !== null) $data['status'] = $this->status;

        return $data;
    }
}
