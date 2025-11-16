<?php

namespace App\Domains\Common\Constants;

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
    public const CREATED = [
        'statusCode' => 201,
        'code' => '201000',
        'type' => 'CREATED',
        'message' => 'Resource created successfully'
    ];

    // ==========================================
    // Error Responses (from OpenAPI)
    // ==========================================
    public const USER_INACTIVE = [
        'statusCode' => 401,
        'code' => '400031',
        'type' => 'UNAUTHORIZED',
        'message' => 'User is inactive.'
    ];
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
    public const INVALID_AUTHORIZATION_FORMAT = [
        'statusCode' => 401,
        'code' => '400011',
        'type' => 'UNAUTHORIZED',
        'message' => 'Invalid Authorization format. Use: Bearer <token>'
    ];
    public const INVALID_TOKEN_FORMAT = [
        'statusCode' => 401,
        'code' => '400021',
        'type' => 'UNAUTHORIZED',
        'message' => 'Invalid token format. Use: admin-{id} or user-{id}'
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

    // ==========================================
    // Auth Errors
    // ==========================================
    public const EMAIL_CONFLICT = [
        'statusCode' => 409,
        'code' => '400010',
        'type' => 'CONFLICT',
        'message' => 'Email already exists.'
    ];
    public const PHONE_CONFLICT = [
        'statusCode' => 409,
        'code' => '400011',
        'type' => 'CONFLICT',
        'message' => 'Phone number already exists.'
    ];
    public const INVALID_CREDENTIALS = [
        'statusCode' => 401,
        'code' => '400012',
        'type' => 'UNAUTHORIZED',
        'message' => 'Invalid credentials.'
    ];
    public const PASSWORD_RESET_TOKEN_INVALID = [
        'statusCode' => 400,
        'code' => '400013',
        'type' => 'BAD_REQUEST',
        'message' => 'Invalid password reset token.'
    ];
    public const PASSWORD_RESET_TOKEN_EXPIRED = [
        'statusCode' => 400,
        'code' => '400014',
        'type' => 'BAD_REQUEST',
        'message' => 'Password reset token has expired.'
    ];
    public const OLD_PASSWORD_INCORRECT = [
        'statusCode' => 400,
        'code' => '400015',
        'type' => 'BAD_REQUEST',
        'message' => 'Old password is incorrect.'
    ];
    public const CANNOT_DELETE_OWN_ACCOUNT = [
        'statusCode' => 400,
        'code' => '400016',
        'type' => 'BAD_REQUEST',
        'message' => 'Cannot delete your own account.'
    ];
    public const INTERNAL_SERVER_ERROR = [
        'statusCode' => 500,
        'code' => '500000',
        'type' => 'INTERNAL_SERVER_ERROR',
        'message' => 'An unexpected error occurred on the server.'
    ];

    // ==========================================
    // Order Errors
    // ==========================================
    const ORDER_CANNOT_UPDATE = [
        'statusCode' => 403,
        'code' => '400019',
        'type' => 'FORBIDDEN',
        'message' => 'Cannot update this order. Current status {status}.'
    ];
    const ORDER_CANNOT_CANCEL = [
        'statusCode' => 403,
        'code' => '400020',
        'type' => 'FORBIDDEN',
        'message' => 'Cannot cancel this order. Current status {status}.'
    ];

    // ==========================================
    // Product Errors
    // ==========================================
    public const PRODUCT_NOT_ACTIVE = [
        'statusCode' => 400,
        'code' => '400018',
        'type' => 'BAD_REQUEST',
        'message' => 'Product {title} is not available'
    ];
    public const PRODUCT_NOT_AVAILABLE = [
        'statusCode' => 400,
        'code' => '400018',
        'type' => 'BAD_REQUEST',
        'message' => 'Not enough product in stock. Available {available}, requested {requested}'
    ];
}

;

