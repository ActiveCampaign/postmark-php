<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Settings for Open webhooks.
 */
class WebhookConfigurationOpenTrigger implements JsonSerializable
{
    private bool $Enabled;
    private bool $PostFirstOpenOnly;

    /**
     * Create a new WebhookConfigurationOpenTrigger.
     *
     * @param bool $enabled           specifies whether or not webhooks will be triggered by Open events
     * @param bool $postFirstOpenOnly if enabled, Open webhooks will only POST on first open
     */
    public function __construct(bool $enabled, bool $postFirstOpenOnly)
    {
        $this->Enabled = $enabled;
        $this->PostFirstOpenOnly = $postFirstOpenOnly;
    }

    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->Enabled,
            'PostFirstOpenOnly' => $this->PostFirstOpenOnly,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

    public function getPostFirstOpenOnly(): bool
    {
        return $this->PostFirstOpenOnly;
    }
}

?>