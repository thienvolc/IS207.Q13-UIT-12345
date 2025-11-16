<?php

namespace App\Applications\DTOs\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseDTO extends JsonResource
{
    protected int $statusCode = 200;
    protected array $meta = [];

    public function withMeta(array $meta): static
    {
        $this->meta = $meta;
        return $this;
    }

    public function withStatus(int $status): static
    {
        $this->statusCode = $status;
        return $this;
    }

    public function toArray($request): array
    {
        return $this->resource;
    }

    public function with(Request $request): array
    {
        return [
            'meta' => $this->meta +
                ['extra_meta' => $this->meta['extra_meta'] ?? (object)[]]
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)
            ->setStatusCode($this->statusCode);
    }
}
