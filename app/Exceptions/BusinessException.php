<?php

namespace App\Exceptions;

use App\Infra\Helpers\StringHelper;
use Exception;

class BusinessException extends Exception
{
    protected array $responseCode;
    protected array $args;
    protected array $extraMeta;

    public function __construct(
        array $responseCode,
        array $args = [],
        array $extraMeta = []
    ) {
        $this->responseCode = $responseCode;
        $this->args = $args;
        $this->extraMeta = $extraMeta;

        $message = StringHelper::applyTemplate($responseCode['message'], $args);
        $this->responseCode['message'] = $message;

        parent::__construct($message, 0, null);
    }

    public function getResponseCode(): array
    {
        return $this->responseCode;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getExtraMeta(): array
    {
        return $this->extraMeta;
    }

    public function getStatusCode(): int
    {
        return $this->responseCode['statusCode'];
    }
}
