<?php

namespace App\Exceptions;

use App\Helpers\StringHelper;
use App\Utils\ResponseFactory;
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

    public function render()
    {
        if (!empty($this->args)) {
            return ResponseFactory::withArgs(
                $this->responseCode,
                $this->args,
                null,
                $this->extraMeta
            );
        }

        return ResponseFactory::error(
            $this->responseCode,
            null,
            $this->extraMeta
        );
    }
}
