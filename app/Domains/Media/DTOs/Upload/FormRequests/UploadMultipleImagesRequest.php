<?php

namespace App\Domains\Media\DTOs\Upload\FormRequests;

use App\Domains\Media\DTOs\Upload\Commands\UploadMultipleImagesDTO;
use Illuminate\Foundation\Http\FormRequest;

class UploadMultipleImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120',
            'folder' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\-_\/]+$/',
        ];
    }

    public function toDTO(): UploadMultipleImagesDTO
    {
        $v = $this->validated();

        return new UploadMultipleImagesDTO(
            files: $v['files'],
            folder: get_string($v, 'folder'),
        );
    }
}
