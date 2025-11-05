<?php

namespace App\Utils;

use App\Helpers\StringHelper;
use Illuminate\Http\JsonResponse;

class ResponseFactory
{
    public static function success(array $responseCode, $data = null, array $extraMeta = []): JsonResponse
    {
        return response()->json(
            self::buildEnvelope($responseCode, $data, $extraMeta),
            $responseCode['statusCode']
        );
    }

    public static function error(array $responseCode, $data = null, array $extraMeta = []): JsonResponse
    {
        return response()->json(
            self::buildEnvelope($responseCode, $data, $extraMeta),
            $responseCode['statusCode']
        );
    }

    private static function buildEnvelope(array $responseCode, $data = null, array $extraMeta = []): array
    {
        return [
            'meta' => [
                'code' => $responseCode['code'],
                'type' => $responseCode['type'],
                'message' => $responseCode['message'],
                'extra_meta' => (object)$extraMeta
            ],
            'data' => $data
        ];
    }

    public static function withArgs(array $responseCode, array $args, $data = null, array $extraMeta = []): JsonResponse
    {
        $message = StringHelper::applyTemplate($responseCode['message'], $args);
        $modifiedCode = array_merge($responseCode, ['message' => $message]);

        return response()->json(
            self::buildEnvelope($modifiedCode, $data, $extraMeta),
            $modifiedCode['statusCode']
        );
    }
}
