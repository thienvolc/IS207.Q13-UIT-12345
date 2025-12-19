<?php

namespace App\Domains\Media\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Media\DTOs\Upload\Commands\UploadImageDTO;
use App\Domains\Media\DTOs\Upload\Commands\UploadMultipleImagesDTO;
use App\Domains\Media\DTOs\Upload\Responses\UploadedImageDTO;
use App\Exceptions\BusinessException;
use Cloudinary\Cloudinary;

class CloudinaryService
{
    private Cloudinary $cloudinary;
    private string $defaultFolder;

    public function __construct()
    {
        $disk = config('filesystems.disks.cloudinary');

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $disk['cloud'],
                'api_key' => $disk['key'],
                'api_secret' => $disk['secret'],
            ],
            'url' => ['secure' => $disk['secure'] ?? true],
        ]);

        $this->defaultFolder = config('cloudinary.folder', 'pinkcapy');
    }

    public function uploadImage(UploadImageDTO $dto): UploadedImageDTO
    {
        $options = [
            'folder' => $dto->folder ?? $this->defaultFolder,
            'resource_type' => 'image',
        ];

        if ($dto->publicId) {
            $options['public_id'] = $dto->publicId;
        }

        $result = $this->cloudinary->uploadApi()->upload(
            $dto->file->getRealPath(),
            $options
        );

        return UploadedImageDTO::fromCloudinaryResponse($result->getArrayCopy());
    }

    public function uploadMultipleImages(UploadMultipleImagesDTO $dto): array
    {
        $folder = $dto->folder ?? $this->defaultFolder;

        return array_map(
            fn($file) => $this->uploadImage(new UploadImageDTO($file, $folder)),
            $dto->files
        );
    }

    public function deleteImage(string $publicId): void
    {
        $result = $this->cloudinary->uploadApi()->destroy($publicId);

        if ($result['result'] !== 'ok') {
            throw new BusinessException(ResponseCode::IMAGE_NOT_FOUND);
        }
    }

    public function deleteMultipleImages(array $publicIds): array
    {
        $results = [];
        foreach ($publicIds as $id) {
            try {
                $this->deleteImage($id);
                $results[$id] = true;
            } catch (BusinessException) {
                $results[$id] = false;
            }
        }
        return $results;
    }
}
