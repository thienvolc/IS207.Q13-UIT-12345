<?php

namespace App\Constants;

class ResponseCode
{
    // ==========================================
    // Success Response
    // ==========================================
    public const SUCCESS = [
        'statusCode' => 200,
        'code' => '200000',
        'type' => 'SUCCESS',
        'message' => 'Success'
    ];

    public const NO_CONTENT = [
        'statusCode' => 204,
        'code' => '200004',
        'type' => 'NO_CONTENT',
        'message' => 'No content.'
    ];

    // ==========================================
    // Error Responses (from OpenAPI)
    // ==========================================

    public const BAD_REQUEST = [
        'statusCode' => 400,
        'code' => '400000',
        'type' => 'BAD_REQUEST',
        'message' => 'Data invalid or incorrect request.'
    ];

    public const UNAUTHORIZED = [
        'statusCode' => 401,
        'code' => '400001',
        'type' => 'UNAUTHORIZED',
        'message' => 'User is not authenticated.'
    ];

    public const VALIDATION_ERROR = [
        'statusCode' => 422,
        'code' => '400002',
        'type' => 'VALIDATION_ERROR',
        'message' => 'Data invalid or missing required fields.'
    ];

    public const FORBIDDEN = [
        'statusCode' => 403,
        'code' => '400003',
        'type' => 'FORBIDDEN',
        'message' => 'User does not have permission to access this resource.'
    ];

    public const NOT_FOUND = [
        'statusCode' => 404,
        'code' => '400004',
        'type' => 'NOT_FOUND',
        'message' => 'Resource not found.'
    ];

    public const CONFLICT = [
        'statusCode' => 409,
        'code' => '400009',
        'type' => 'CONFLICT',
        'message' => 'Resource already exists.'
    ];

    public const INTERNAL_SERVER_ERROR = [
        'statusCode' => 500,
        'code' => '500000',
        'type' => 'INTERNAL_SERVER_ERROR',
        'message' => 'An unexpected error occurred on the server.'
    ];
}

