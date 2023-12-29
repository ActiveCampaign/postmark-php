<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Settings for Click webhooks.
 */
class WebhookConfigurationClickTrigger implements JsonSerializable
{
    private $enabled;

    /**
     * Create a new WebhookConfigurationClickTrigger.
     *
     * @param bool $enabled specifies whether the webhooks will be triggered by Click events
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
