<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppression
{
    public string $EmailAddress;
    public string $SuppressionReason;
    public string $Origin;
    public string $CreatedAt;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->EmailAddress = !empty($values['EmailAddress']) ? $values['EmailAddress'] : "";
        $this->SuppressionReason = !empty($values['SuppressionReason']) ? $values['SuppressionReason'] : "";
        $this->Origin = !empty($values['Origin']) ? $values['Origin'] : "";
        $this->CreatedAt = !empty($values['CreatedAt']) ? $values['CreatedAt'] : "";
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
     * @return PostmarkSuppression
     */
    public function setEmailAddress(string $EmailAddress): PostmarkSuppression
    {
        $this->EmailAddress = $EmailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuppressionReason(): string
    {
        return $this->SuppressionReason;
    }

    /**
     * @param string $SuppressionReason
     * @return PostmarkSuppression
     */
    public function setSuppressionReason(string $SuppressionReason): PostmarkSuppression
    {
        $this->SuppressionReason = $SuppressionReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->Origin;
    }

    /**
     * @param string $Origin
     * @return PostmarkSuppression
     */
    public function setOrigin(string $Origin): PostmarkSuppression
    {
        $this->Origin = $Origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->CreatedAt;
    }

    /**
     * @param string $CreatedAt
     * @return PostmarkSuppression
     */
    public function setCreatedAt(string $CreatedAt): PostmarkSuppression
    {
        $this->CreatedAt = $CreatedAt;
        return $this;
    }


}