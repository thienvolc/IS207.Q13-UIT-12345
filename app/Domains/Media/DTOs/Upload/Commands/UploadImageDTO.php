<?php

namespace App\Domains\Media\DTOs\Upload\Commands;

use Illuminate\Http\UploadedFile;

readonly class UploadImageDTO
{
    public function __construct(
        public UploadedFile $file,
        public ?string $folder = null,
        public ?string $publicId = null,
    ) {
    }
}
