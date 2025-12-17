<?php

namespace App\Http\Controllers\Api\Shared;

use App\Domains\Media\DTOs\Upload\FormRequests\DeleteMultipleImagesRequest;
use App\Domains\Media\DTOs\Upload\FormRequests\UploadImageRequest;
use App\Domains\Media\DTOs\Upload\FormRequests\UploadMultipleImagesRequest;
use App\Domains\Media\Services\CloudinaryService;
use App\Http\Controllers\AppController;

class FileUploadController extends AppController
{
    public function __construct(
        private readonly CloudinaryService $cloudinaryService
    ) {}

    // [POST] /upload/image
    public function uploadImage(UploadImageRequest $request)
    {
        $result = $this->cloudinaryService->uploadImage($request->toDTO());

        return $this->created($result->toArray());
    }

    // [POST] /upload/images
    public function uploadMultipleImages(UploadMultipleImagesRequest $request)
    {
        $results = $this->cloudinaryService->uploadMultipleImages($request->toDTO());

        $uploadedData = array_map(fn($img) => $img->toArray(), $results);

        return $this->created($uploadedData);
    }

    // [DELETE] /upload/image/{publicId}
    public function deleteImage(string $publicId)
    {
        $this->cloudinaryService->deleteImage(urldecode($publicId));

        return $this->noContent();
    }

    // [DELETE] /upload/images
    public function deleteMultipleImages(DeleteMultipleImagesRequest $request)
    {
        $results = $this->cloudinaryService->deleteMultipleImages($request->validated()['public_ids']);

        return $this->success($results);
    }
}
