<?php

namespace App\Http\Traits;

use App\Constants\ResponseCode;
use App\Utils\ResponseFactory;
use Illuminate\Http\JsonResponse;

trait AppResponse
{
    protected function successResponse($data = null, array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::success(ResponseCode::SUCCESS, $data, $extraMeta);
    }

    protected function createdResponse($data = null, array $extraMeta = []): JsonResponse
    {
        $created = [
            'statusCode' => 201,
            'code' => '201000',
            'type' => 'CREATED',
            'message' => 'Resource created successfully'
        ];
        return ResponseFactory::success($created, $data, $extraMeta);
    }

    protected function errorResponse(array $responseCode, $data = null, array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::error($responseCode, $data, $extraMeta);
    }

    protected function notFoundResponse(array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::error(ResponseCode::NOT_FOUND, null, $extraMeta);
    }

    protected function validationErrorResponse(array $errors, array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::error(ResponseCode::VALIDATION_ERROR, ['errors' => $errors], $extraMeta);
    }

    protected function conflictResponse(array $extraMeta = []): JsonResponse
    {
        return ResponseFactory::error(ResponseCode::CONFLICT, null, $extraMeta);
    }

    protected function paginatedResponse(
        $items,
        int $total,
        int $page,
        int $perPage,
        array $extraMeta = []
    ): JsonResponse {
        $data = [
            'items' => $items,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ]
        ];

        return ResponseFactory::success(ResponseCode::SUCCESS, $data, $extraMeta);
    }

    protected function success(
        array|object|null $data = null,
        string $message = 'Success',
        string $code = '200000',
        array $extraMeta = []
    ): JsonResponse {
        $responseCode = [
            'statusCode' => 200,
            'code' => $code,
            'type' => 'SUCCESS',
            'message' => $message
        ];
        return ResponseFactory::success($responseCode, $data, $extraMeta);
    }

    protected function error(
        string $message,
        string $code = '400000',
        int $httpStatus = 400,
        array $extraMeta = []
    ): JsonResponse {
        $responseCode = [
            'statusCode' => $httpStatus,
            'code' => $code,
            'type' => 'ERROR',
            'message' => $message
        ];
        return ResponseFactory::error($responseCode, null, $extraMeta);
    }
}
