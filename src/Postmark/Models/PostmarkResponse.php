<?php

namespace Postmark\Models;

class PostmarkResponse
{
    public int $ErrorCode;
    public ?string $Message;

    public function __construct(array $values)
    {
        $this->ErrorCode = !empty($values['ErrorCode']) ? $values['ErrorCode'] : 0;
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
    }

    public function getErrorCode(): int
    {
        return $this->ErrorCode;
    }

    public function setErrorCode(int $ErrorCode): PostmarkResponse
    {
        $this->ErrorCode = $ErrorCode;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(?string $Message): PostmarkResponse
    {
        $this->Message = $Message;

        return $this;
    }
}
