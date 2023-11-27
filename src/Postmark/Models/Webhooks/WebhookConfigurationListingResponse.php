<?php

namespace Postmark\Models\Webhooks;

class WebhookConfigurationListingResponse
{
    public array $Webhooks;

    public function __construct(array $webhooks)
    {
        $this->Webhooks = [];
        $this->setWebhooks($webhooks);
    }

    public function getWebhooks(): array
    {
        return $this->Webhooks;
    }

    public function setWebhooks(array $Webhooks): WebhookConfigurationListingResponse
    {
        $tempHooks = [];
        foreach ($Webhooks['Webhooks'] as $webhook) {
            $obj = json_decode(json_encode($webhook));
            $triggers = new WebhookConfigurationTriggers();
            $httpAuth = new HttpAuth();
            $webhookConfig = new WebhookConfiguration((int) $obj->ID, $obj->Url, $obj->MessageStream, $httpAuth, [], $triggers);

            $tempHooks[] = $webhookConfig;
        }
        $this->Webhooks = $tempHooks;

        return $this;
    }
}
