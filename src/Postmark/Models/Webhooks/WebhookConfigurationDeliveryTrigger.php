<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for Delivery webhooks.
 */
class WebhookConfigurationDeliveryTrigger implements WebhookConfiguration
{
    /**
     * Create a new WebhookConfigurationDeliveryTrigger.
     *
     * @param bool $enabled Specifies whether webhooks will be triggered by Delivery events.
     */
    public function __construct(private bool $enabled = false)
    {
    }

    /** @return array{Enabled: bool} */
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
