<?php

namespace App\Domains\Media\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Media\DTOs\Upload\Commands\UploadImageDTO;
use App\Domains\Media\DTOs\Upload\Commands\UploadMultipleImagesDTO;
use App\Domains\Media\DTOs\Upload\Responses\UploadedImageDTO;
use App\Exceptions\BusinessException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
    private string $defaultFolder;

    public function __construct()
    {
        $this->defaultFolder = config('cloudinary.folder', 'pinkcapy');
    }

    public function uploadImage(UploadImageDTO $dto): UploadedImageDTO
    {
        $folder = $dto->folder ?? $this->defaultFolder;

        $options = [
            'folder' => $folder,
            'resource_type' => 'image',
        ];

        if ($dto->publicId) {
            $options['public_id'] = $dto->publicId;
        }

        $result = Cloudinary::upload($dto->file->getRealPath(), $options);

        return UploadedImageDTO::fromCloudinaryResponse($result->getResponse());
    }

    public function uploadMultipleImages(UploadMultipleImagesDTO $dto): array
    {
        $uploadedImages = [];
        $targetFolder = $dto->folder ?? $this->defaultFolder;

        foreach ($dto->files as $file) {
            $imageDto = new UploadImageDTO(
                file: $file,
                folder: $targetFolder,
            );
            $uploadedImages[] = $this->uploadImage($imageDto);
        }

        return $uploadedImages;
    }

    public function deleteImage(string $publicId): void
    {
        $result = Cloudinary::destroy($publicId);

        if ($result->getResponse()['result'] !== 'ok') {
            throw new BusinessException(ResponseCode::IMAGE_NOT_FOUND);
        }
    }

    public function deleteMultipleImages(array $publicIds): array
    {
        $results = [];

        foreach ($publicIds as $publicId) {
            try {
                $this->deleteImage($publicId);
                $results[$publicId] = true;
            } catch (BusinessException) {
                $results[$publicId] = false;
            }
        }

        return $results;
    }
}
