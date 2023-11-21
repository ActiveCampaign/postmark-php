<?php
namespace Postmark\Models\Suppressions;

use JetBrains\PhpStorm\ArrayShape;

/**
 * Model describing a request to suppress or reactivate one recipient.
 */
class SuppressionChangeRequest implements \JsonSerializable {

    private string $EmailAddress;

    /**
     * Create a new SuppressionChangeRequest.
     *
     * @param string $emailAddress Address of the recipient whose suppression status should be changed.
     */
    public function __construct($emailAddress = null) {
        $this->EmailAddress = $emailAddress;
    }

    public function jsonSerialize(): array
    {
        $retval = array(
            "EmailAddress" => $this->EmailAddress
        );

        return $retval;
    }

    public function getEmailAddress(): ?string
    {
        return $this->EmailAddress;
    }
}
