<?php

namespace App\Exceptions;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Applications\Services\ResponseFactory;
use App\Domains\Common\Constants\ResponseCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [
        BusinessException::class,
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (BusinessException $e) {
            return ResponseFactory::error(
                $e->getResponseCode(),
                null,
                $e->getExtraMeta()
            );
        });

        $this->renderable(function (ValidationException $e) {
            return ResponseFactory::error(
                ResponseCode::VALIDATION_ERROR,
                ['errors' => $e->errors()]
            );
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return ResponseFactory::error(ResponseCode::NOT_FOUND);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return ResponseFactory::error(ResponseCode::NOT_FOUND);
        });

        $this->renderable(function (AuthenticationException $e) {
            return ResponseFactory::error(ResponseCode::UNAUTHORIZED);
        });

        $this->renderable(function (HttpException $e) {
            $statusCode = $e->getStatusCode();

            return match ($statusCode) {
                400 => ResponseFactory::error(ResponseCode::BAD_REQUEST),
                401 => ResponseFactory::error(ResponseCode::UNAUTHORIZED),
                403 => ResponseFactory::error(ResponseCode::FORBIDDEN),
                404 => ResponseFactory::error(ResponseCode::NOT_FOUND),
                409 => ResponseFactory::error(ResponseCode::CONFLICT),
                default => ResponseFactory::error(ResponseCode::INTERNAL_SERVER_ERROR)
            };
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception): ResponseDTO
    {
        return ResponseFactory::error(ResponseCode::UNAUTHORIZED);
    }

    protected function invalidJson($request, ValidationException $exception): ResponseDTO
    {
        return ResponseFactory::error(
            ResponseCode::VALIDATION_ERROR,
            ['errors' => $exception->errors()]
        );
    }
}
