<?php

namespace App\Http\Traits;

use App\Constants\ResponseCode;
use App\Utils\ResponseFactory;
use Illuminate\Http\JsonResponse;

trait AppResponse
{
    protected function success($data = null, array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::success(ResponseCode::SUCCESS, $data, $extraMeta);
    }

    protected function created($data = null, array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::success(ResponseCode::CREATED, $data, $extraMeta);
    }

    protected function noContent(): JsonResponse
    {
        return ResponseFactory::success(ResponseCode::NO_CONTENT);
    }
}
