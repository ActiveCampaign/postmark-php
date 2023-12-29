<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppressionRequestResult
{
    public string $EmailAddress;
    public string $Status;
    public string $Message;

    public function __construct(array $values)
    {
        $this->EmailAddress = !empty($values['EmailAddress']) ? $values['EmailAddress'] : '';
        $this->Status = !empty($values['Status']) ? $values['Status'] : '';
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
    }

    public function getEmailAddress(): string
    {
        return $this->EmailAddress;
    }

    public function setEmailAddress(string $EmailAddress): PostmarkSuppressionRequestResult
    {
        $this->EmailAddress = $EmailAddress;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): PostmarkSuppressionRequestResult
    {
        $this->Status = $Status;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): PostmarkSuppressionRequestResult
    {
        $this->Message = $Message;

        return $this;
    }
}
