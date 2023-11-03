<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\WebhookConfiguration;

class WebhookConfigurationListingResponse
{
    public array $Webhooks;

    /**
     * @param array $Webhooks
     */
    public function __construct(array $Webhooks)
    {
        $this->setWebhooks($Webhooks);
    }

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
        foreach ($Webhooks as $webhook)
        {
            $this->Webhooks[] = json_decode(json_encode($webhook));;
        }
        return $this;
    }
}