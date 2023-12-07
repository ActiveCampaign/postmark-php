<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

class WebhookConfigurationBounceTrigger implements JsonSerializable
{
    private $Enabled;
    private $IncludeContent;

    /**
     * Create a new WebhookConfigurationBounceTrigger.
     *
     * @param bool $enabled        specifies whether the webhooks will be triggered by Bounce events
     * @param bool $includeContent specifies whether the full content of the email bounce is included in webhook POST
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
