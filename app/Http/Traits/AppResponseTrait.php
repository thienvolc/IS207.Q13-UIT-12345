<?php

namespace App\Http\Traits;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Applications\Services\ResponseFactory;
use App\Domains\Common\Constants\ResponseCode;

trait AppResponseTrait
{
    protected function success($data = null, array $extraMeta = []): ResponseDTO
    {
        return ResponseFactory::success(ResponseCode::SUCCESS, $data, $extraMeta);
    }

    protected function created($data = null, array $extraMeta = []): ResponseDTO
    {
        return ResponseFactory::success(ResponseCode::CREATED, $data, $extraMeta);
    }

    protected function noContent(): ResponseDTO
    {
        return ResponseFactory::success(ResponseCode::NO_CONTENT);
    }
}
