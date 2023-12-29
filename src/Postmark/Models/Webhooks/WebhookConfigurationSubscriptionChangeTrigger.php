<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Settings for SubscriptionChange webhooks.
 */
class WebhookConfigurationSubscriptionChangeTrigger implements JsonSerializable
{
    private $Enabled;

    /**
     * Create a new WebhookConfigurationSubscriptionChangeTrigger.
     *
     * @param bool $enabled specifies whether webhooks will be triggered by SubscriptionChange events
     */
    public function __construct(bool $enabled)
    {
        $this->Enabled = $enabled;
    }

    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->Enabled,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }
}
