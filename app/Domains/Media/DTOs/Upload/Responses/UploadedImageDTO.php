<?php

namespace App\Domains\Media\DTOs\Upload\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class UploadedImageDTO implements BaseDTO
{
    public function __construct(
        public string $publicId,
        public string $url,
        public string $secureUrl,
        public string $format,
        public int $width,
        public int $height,
        public int $bytes,
        public ?string $folder = null,
    ) {
    }

    public static function fromCloudinaryResponse(array $response): self
    {
        return new self(
            publicId: $response['public_id'],
            url: $response['url'],
            secureUrl: $response['secure_url'],
            format: $response['format'],
            width: $response['width'],
            height: $response['height'],
            bytes: $response['bytes'],
            folder: $response['folder'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'public_id' => $this->publicId,
            'url' => $this->url,
            'secure_url' => $this->secureUrl,
            'format' => $this->format,
            'width' => $this->width,
            'height' => $this->height,
            'bytes' => $this->bytes,
            'folder' => $this->folder,
        ];
    }
}
