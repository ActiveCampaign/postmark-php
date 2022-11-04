<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for Open webhooks.
 */
class WebhookConfigurationOpenTrigger implements WebhookConfiguration
{
    /**
     * Create a new WebhookConfigurationOpenTrigger.
     *
     * @param bool $enabled           Specifies whether webhooks will be triggered by Open events.
     * @param bool $postFirstOpenOnly If enabled, Open webhooks will only POST on first open.
     */
    public function __construct(private bool $enabled = false, private bool $postFirstOpenOnly = false)
    {
    }

    /** @return array{Enabled: bool, PostFirstOpenOnly: bool} */
    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->enabled,
            'PostFirstOpenOnly' => $this->postFirstOpenOnly,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getPostFirstOpenOnly(): bool
    {
        return $this->postFirstOpenOnly;
    }
}
