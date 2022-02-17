<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for SpamComplaint webhooks.
 */
class WebhookConfigurationSpamComplaintTrigger implements WebhookConfiguration
{
    private bool $enabled;
    private bool $includeContent;

    /**
     * Create a new WebhookConfigurationSpamComplaintTrigger.
     *
     * @param bool $enabled        Specifies whether webhooks will be triggered by SpamComplaint events.
     * @param bool $includeContent Specifies whether the full content of the spam complaint is included in webhook POST.
     */
    public function __construct(bool $enabled = false, bool $includeContent = false)
    {
        $this->enabled = $enabled;
        $this->includeContent = $includeContent;
    }

    /** @return array{Enabled: bool, IncludeContent: bool} */
    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->enabled,
            'IncludeContent' => $this->includeContent,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getIncludeContent(): bool
    {
        return $this->includeContent;
    }
}
