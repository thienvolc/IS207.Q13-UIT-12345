<?php

namespace App\Applications\Services;

use App\Applications\DTOs\Responses\ResponseDTO;

class ResponseFactory
{
    public static function success(array $responseCode, $data = null, array $extraMeta = []): ResponseDTO
    {
        return (new ResponseDTO($data))
            ->withMeta(self::buildMeta($responseCode, $extraMeta))
            ->withStatus($responseCode['statusCode']);
    }

    public static function error(array $responseCode, $data = null, array $extraMeta = []): ResponseDTO
    {
        return (new ResponseDTO($data))
            ->withMeta(self::buildMeta($responseCode, $extraMeta))
            ->withStatus($responseCode['statusCode']);
    }

    private static function buildMeta(array $responseCode, array $extraMeta = []): array
    {
        return [
            'code' => $responseCode['code'],
            'type' => $responseCode['type'],
            'message' => $responseCode['message'],
            'extra_meta' => (object)$extraMeta
        ];
    }
}
