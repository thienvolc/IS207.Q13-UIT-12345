<?php

namespace App\Domains\Transaction\DTOs\Requests;

readonly class CreateTransactionDTO
{
    public function __construct(
        public int     $orderId,
        public float   $amount,
        public ?string $content,
        public ?string $code,
        public int     $type,
        public ?string $mode,
        public ?int    $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            orderId: $data['orderId'],
            amount: $data['amount'],
            content: $data['content'] ?? null,
            code: $data['code'] ?? null,
            type: $data['type'],
            mode: $data['mode'] ?? null,
            status: $data['status'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'content' => $this->content,
            'code' => $this->code,
            'type' => $this->type,
            'mode' => $this->mode,
            'status' => $this->status,
        ];
    }
}
