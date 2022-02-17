<?php

declare(strict_types=1);

namespace Postmark\Models\Suppressions;

use JsonSerializable;

/**
 * Model describing a request to suppress or reactivate one recipient.
 */
class SuppressionChangeRequest implements JsonSerializable
{
    private ?string $emailAddress;

    /**
     * Create a new SuppressionChangeRequest.
     *
     * @param string|null $emailAddress Address of the recipient whose suppression status should be changed.
     */
    public function __construct(?string $emailAddress = null)
    {
        $this->emailAddress = $emailAddress;
    }

    /** @return array{EmailAddress: string|null} */
    public function jsonSerialize(): array
    {
        return [
            'EmailAddress' => $this->emailAddress,
        ];
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }
}
