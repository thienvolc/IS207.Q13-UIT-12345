<?php

namespace App\Domains\Media\DTOs\Upload\Commands;

readonly class UploadMultipleImagesDTO
{
    public function __construct(
        public array $files,
        public ?string $folder = null,
    ) {
    }
}
