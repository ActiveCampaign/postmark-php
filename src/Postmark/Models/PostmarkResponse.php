<?php

namespace Postmark\Models;

class PostmarkResponse
{
    public string $MessageID;
    public string $PostmarkStatus;
    public ?string $Message;
    public string $SubmittedAt;
    public string $To;
    public int $ErrorCode;

    public function __construct(array $values)
    {
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
        $this->PostmarkStatus = !empty($values['PostmarkStatus']) ? $values['PostmarkStatus'] : '';
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
        $this->SubmittedAt = !empty($values['SubmittedAt']) ? $values['SubmittedAt'] : '';
        $this->To = !empty($values['To']) ? $values['To'] : '';
        $this->ErrorCode = !empty($values['ErrorCode']) ? $values['ErrorCode'] : 0;
    }

    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    public function setMessageID(string $MessageID): PostmarkResponse
    {
        $this->MessageID = $MessageID;

        return $this;
    }

    public function getPostmarkStatus(): string
    {
        return $this->PostmarkStatus;
    }

    public function setPostmarkStatus(string $PostmarkStatus): PostmarkResponse
    {
        $this->PostmarkStatus = $PostmarkStatus;

        return $this;
    }

    /**
     * @return null|mixed|string
     */
    public function getMessage(): mixed
    {
        return $this->Message;
    }

    /**
     * @param null|mixed|string $Message
     */
    public function setMessage(mixed $Message): PostmarkResponse
    {
        $this->Message = $Message;

        return $this;
    }

    public function getSubmittedAt(): string
    {
        return $this->SubmittedAt;
    }

    public function setSubmittedAt(string $SubmittedAt): PostmarkResponse
    {
        $this->SubmittedAt = $SubmittedAt;

        return $this;
    }

    public function getTo(): string
    {
        return $this->To;
    }

    public function setTo(string $To): PostmarkResponse
    {
        $this->To = $To;

        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getErrorCode(): mixed
    {
        return $this->ErrorCode;
    }

    /**
     * @param int|mixed $ErrorCode
     */
    public function setErrorCode(mixed $ErrorCode): PostmarkResponse
    {
        $this->ErrorCode = $ErrorCode;

        return $this;
    }
}
