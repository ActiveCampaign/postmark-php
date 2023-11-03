<?php

namespace Postmark\Models\Webhooks;

class WebhookConfigurationListingResponse
{
    public array $Webhooks;

    /**
     * @return array
     */
    public function getWebhooks(): array
    {
        return $this->Webhooks;
    }

    /**
     * @param array $Webhooks
     * @return WebhookConfigurationListingResponse
     */
    public function setWebhooks(array $Webhooks): WebhookConfigurationListingResponse
    {
        $this->Webhooks = $Webhooks;
        return $this;
    }
}