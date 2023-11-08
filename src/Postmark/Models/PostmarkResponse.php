<?php

namespace Postmark\Models;

class PostmarkResponse
{
    public int $ErrorCode;
    public ?string $Message;

    public function __construct(int $errorCode, ?string $message = null)
    {
        $this->ErrorCode = $errorCode;
        $this->Message = $message;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->ErrorCode;
    }

    /**
     * @param int $ErrorCode
     * @return PostmarkResponse
     */
    public function setErrorCode(int $ErrorCode): PostmarkResponse
    {
        $this->ErrorCode = $ErrorCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->Message;
    }

    /**
     * @param string|null $Message
     * @return PostmarkResponse
     */
    public function setMessage(?string $Message): PostmarkResponse
    {
        $this->Message = $Message;
        return $this;
    }
}