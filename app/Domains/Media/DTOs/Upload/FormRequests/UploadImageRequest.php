<?php

namespace App\Domains\Media\DTOs\Upload\FormRequests;

use App\Domains\Media\DTOs\Upload\Commands\UploadImageDTO;
use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120',
            'folder' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\-_\/]+$/',
            'public_id' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\-_]+$/',
        ];
    }

    public function toDTO(): UploadImageDTO
    {
        $v = $this->validated();

        return new UploadImageDTO(
            file: $v['file'],
            folder: get_string($v, 'folder'),
            publicId: get_string($v, 'public_id'),
        );
    }
}
