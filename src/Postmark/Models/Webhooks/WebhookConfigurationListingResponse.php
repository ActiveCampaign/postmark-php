<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\Models\Webhooks\HttpAuth;

class WebhookConfigurationListingResponse
{
    public array $Webhooks;

    /**
     * @param array $webhooks
     */
    public function __construct(array $webhooks)
    {
        $this->Webhooks = array();
        $this->setWebhooks($webhooks);
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
        $tempHooks = array();
        foreach ($Webhooks['Webhooks'] as $webhook)
        {
            $obj = json_decode(json_encode($webhook));
            $triggers = new WebhookConfigurationTriggers();
            $httpAuth = new HttpAuth();
            $webhookConfig = new WebhookConfiguration((int)$obj->ID, $obj->Url, $obj->MessageStream, $httpAuth, array(), $triggers);

            $tempHooks[] = $webhookConfig;
        }
        $this->Webhooks = $tempHooks;

        return $this;
    }
}