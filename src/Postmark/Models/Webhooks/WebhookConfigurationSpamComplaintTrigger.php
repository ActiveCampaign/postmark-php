<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Settings for SpamComplaint webhooks.
 */
class WebhookConfigurationSpamComplaintTrigger implements JsonSerializable
{
    private $Enabled;
    private $IncludeContent;

    /**
     * Create a new WebhookConfigurationSpamComplaintTrigger.
     *
     * @param bool $enabled        specifies whether or not webhooks will be triggered by SpamComplaint events
     * @param bool $includeContent specifies whether or not the full content of the spam complaint is included in webhook POST
     */
    public function __construct(bool $enabled, bool $includeContent)
    {
        $this->Enabled = $enabled;
        $this->IncludeContent = $includeContent;
    }

    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->Enabled,
            'IncludeContent' => $this->IncludeContent,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

    public function getIncludeContent(): bool
    {
        return $this->IncludeContent;
    }
}
