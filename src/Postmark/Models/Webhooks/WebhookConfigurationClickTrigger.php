<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for Click webhooks.
 */
class WebhookConfigurationClickTrigger implements WebhookConfiguration
{
    private bool $enabled;

    /**
     * Create a new WebhookConfigurationClickTrigger.
     *
     * @param bool $enabled Specifies whether webhooks will be triggered by Click events.
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
