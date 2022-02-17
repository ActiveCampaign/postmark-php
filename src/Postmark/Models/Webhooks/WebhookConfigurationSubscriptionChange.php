<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for SubscriptionChange webhooks.
 */
class WebhookConfigurationSubscriptionChange implements WebhookConfiguration
{
    private bool $enabled;

    /**
     * Create a new WebhookConfigurationSubscriptionChangeTrigger.
     *
     * @param bool $enabled Specifies whether webhooks will be triggered by SubscriptionChange events.
     */
    public function __construct(bool $enabled = false)
    {
        $this->enabled = $enabled;
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
