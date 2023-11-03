<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;

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
            $obj = json_decode(json_encode($webhook));
            // TODO add null checks
            $triggers = new WebhookConfigurationTriggers($obj->Triggers->Open, $obj->Triggers->Click, $obj->Triggers->Delivery, $obj->Triggers->Bounce, $obj->Triggers->SpamComplaint, $obj->Triggers->SubscriptionChange);
            $webhookConfig = new WebhookConfiguration((int)$obj->ID, $obj->Url, $obj->MessageStream, null, null, $triggers);

            $this->Webhooks[] = $webhookConfig;
        }
        return $this;
    }
}