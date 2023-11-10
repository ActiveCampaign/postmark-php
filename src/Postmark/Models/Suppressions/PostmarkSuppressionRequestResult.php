<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppressionRequestResult
{
    public string $EmailAddress;
    public string $Status;
    public string $Message;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->EmailAddress = !empty($values['EmailAddress']) ? $values['EmailAddress'] : "";
        $this->Status = !empty($values['Status']) ? $values['Status'] : "";
        $this->Message = !empty($values['Message']) ? $values['Message'] : "";
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->EmailAddress;
    }

    /**
     * @param string $EmailAddress
     * @return PostmarkSuppressionRequestResult
     */
    public function setEmailAddress(string $EmailAddress): PostmarkSuppressionRequestResult
    {
        $this->EmailAddress = $EmailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return PostmarkSuppressionRequestResult
     */
    public function setStatus(string $Status): PostmarkSuppressionRequestResult
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->Message;
    }

    /**
     * @param string $Message
     * @return PostmarkSuppressionRequestResult
     */
    public function setMessage(string $Message): PostmarkSuppressionRequestResult
    {
        $this->Message = $Message;
        return $this;
    }
}