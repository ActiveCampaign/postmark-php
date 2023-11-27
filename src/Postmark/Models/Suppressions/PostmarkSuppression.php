<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppression
{
    public string $EmailAddress;
    public string $SuppressionReason;
    public string $Origin;
    public string $CreatedAt;

    public function __construct(array $values)
    {
        $this->EmailAddress = !empty($values['EmailAddress']) ? $values['EmailAddress'] : '';
        $this->SuppressionReason = !empty($values['SuppressionReason']) ? $values['SuppressionReason'] : '';
        $this->Origin = !empty($values['Origin']) ? $values['Origin'] : '';
        $this->CreatedAt = !empty($values['CreatedAt']) ? $values['CreatedAt'] : '';
    }

    public function getEmailAddress(): string
    {
        return $this->EmailAddress;
    }

    public function setEmailAddress(string $EmailAddress): PostmarkSuppression
    {
        $this->EmailAddress = $EmailAddress;

        return $this;
    }

    public function getSuppressionReason(): string
    {
        return $this->SuppressionReason;
    }

    public function setSuppressionReason(string $SuppressionReason): PostmarkSuppression
    {
        $this->SuppressionReason = $SuppressionReason;

        return $this;
    }

    public function getOrigin(): string
    {
        return $this->Origin;
    }

    public function setOrigin(string $Origin): PostmarkSuppression
    {
        $this->Origin = $Origin;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(string $CreatedAt): PostmarkSuppression
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }
}
