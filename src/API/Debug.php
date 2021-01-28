<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API;

/**
 * Debug helper class
 */
class Debug
{
    /**
     * @var mixed
     */
    public $lastRequestBody;
    /**
     * @var mixed
     */
    public $lastRequestHeaders;
    /**
     * @var mixed
     */
    public $lastResponseCode;
    public string $lastResponseHeaders;
    /**
     * @var mixed
     */
    public $lastResponseError;

    public function __toString(): string
    {
        $lastError = $this->lastResponseError;
        if (! is_string($lastError)) {
            $lastError = json_encode($lastError);
        }
        return 'LastResponseCode: '.$this->lastResponseCode
            .', LastResponseError: '.$lastError
            .', LastResponseHeaders: '.$this->lastResponseHeaders
            .', LastRequestHeaders: '.$this->lastRequestHeaders
            .', LastRequestBody: '.$this->lastRequestBody;
    }
}
