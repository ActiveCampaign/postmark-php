<?php

namespace Postmark\Models\Suppressions;

use JsonSerializable;

/**
 * Model describing a request to suppress or reactivate one recipient.
 */
class SuppressionChangeRequest implements JsonSerializable
{
    private string $EmailAddress;

    /**
     * Create a new SuppressionChangeRequest.
     *
     * @param string $emailAddress address of the recipient whose suppression status should be changed
     */
    public function __construct($emailAddress = null)
    {
        $this->EmailAddress = $emailAddress;
    }

    public function jsonSerialize(): array
    {
        return [
            'EmailAddress' => $this->EmailAddress,
        ];
    }

    public function getEmailAddress(): ?string
    {
        return $this->EmailAddress;
    }
}
