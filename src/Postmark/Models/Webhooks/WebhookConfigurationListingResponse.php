<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\Models\Webhooks\HttpAuth;

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
        //fwrite(STDERR, "first setWebhooks -------------------------!!! ". print_r($Webhooks, TRUE));
        foreach ($Webhooks['Webhooks'] as $webhook)
        {
            $obj = json_decode(json_encode($webhook));
            // TODO add null checks
            fwrite(STDERR, "second setWebhooks -------------------------!!! ". print_r($obj, TRUE));
            $triggers = new WebhookConfigurationTriggers();
            $httpAuth = new HttpAuth();
            $webhookConfig = new WebhookConfiguration((int)$obj->ID, $obj->Url, $obj->MessageStream, $httpAuth, array(), $triggers);

            $this->Webhooks[] = $webhookConfig;
        }
        return $this;
    }
}