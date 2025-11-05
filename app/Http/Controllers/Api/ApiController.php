<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function success(
        array|object|null $data = null,
        string $message = 'Success',
        string $code = '200000',
        array $extraMeta = []): JsonResponse {
        return response()->json([
            'meta' => [
                'code' => $code,
                'type' => 'SUCCESS',
                'message' => $message,
                'extra_meta' => (object) $extraMeta
            ],
            'data' => $data
        ]);
    }

    protected function error(
        string $message,
        string $code = '400000',
        int $httpStatus = 400,
        array $extraMeta = []): JsonResponse {
        return response()->json([
            'meta' => [
                'code' => $code,
                'type' => 'ERROR',
                'message' => $message,
                'extra_meta' => (object) $extraMeta
            ],
            'data' => null
        ], $httpStatus);
    }
}

