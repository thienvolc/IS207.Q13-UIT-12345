<?php

namespace App\Exceptions;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Applications\Services\ResponseFactory;
use App\Domains\Common\Constants\ResponseCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler
{
    public function handle(Throwable $e, $request): JsonResponse
    {
        $response = match (true) {
            $e instanceof BusinessException         => $this->business($e),
            $e instanceof ValidationException       => $this->invalidJson($e),
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException     => $this->notFound(),
            $e instanceof AuthenticationException   => $this->unauthenticated($e),
            $e instanceof HttpException             => $this->http($e),
            default                                 => $this->internal(),
        };

        return $response->toResponse($request);
    }

    private function business(BusinessException $e): ResponseDTO
    {
        return ResponseFactory::error($e->getResponseCode(), null, $e->getExtraMeta());
    }

    protected function unauthenticated(AuthenticationException $e): ResponseDTO
    {
        return ResponseFactory::error(ResponseCode::UNAUTHORIZED);
    }

    protected function invalidJson(ValidationException $e): ResponseDTO
    {
        return ResponseFactory::error(ResponseCode::VALIDATION_ERROR, ['errors' => $e->errors()]);
    }

    private function notFound(): ResponseDTO
    {
        return ResponseFactory::error(ResponseCode::NOT_FOUND);
    }

    private function http(HttpException|Throwable $e): ResponseDTO
    {
        $status = $e->getStatusCode();

        return match ($status) {
            400 => ResponseFactory::error(ResponseCode::BAD_REQUEST),
            401 => ResponseFactory::error(ResponseCode::UNAUTHORIZED),
            403 => ResponseFactory::error(ResponseCode::FORBIDDEN),
            404 => ResponseFactory::error(ResponseCode::NOT_FOUND),
            409 => ResponseFactory::error(ResponseCode::CONFLICT),
            default => ResponseFactory::error(ResponseCode::INTERNAL_SERVER_ERROR),
        };
    }

    private function internal(): ResponseDTO
    {
        return ResponseFactory::error(ResponseCode::INTERNAL_SERVER_ERROR);
    }
}
