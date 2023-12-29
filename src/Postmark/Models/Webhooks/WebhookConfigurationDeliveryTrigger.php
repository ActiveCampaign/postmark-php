<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Settings for Delivery webhooks.
 */
class WebhookConfigurationDeliveryTrigger implements JsonSerializable
{
    private $enabled;

    /**
     * Create a new WebhookConfigurationDeliveryTrigger.
     *
     * @param bool $enabled specifies whether or not webhooks will be triggered by Delivery events
     */
    public function __construct($enabled = false)
    {
        $this->enabled = $enabled;
    }

    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->enabled,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}
