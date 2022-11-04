<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

use JsonSerializable;

use function array_filter;

/**
 * All triggers available for a WebhookConfiguration.
 */
class WebhookConfigurationTriggers implements JsonSerializable
{
    /**
     * Create a new WebhookConfigurationTriggers object.
     */
    public function __construct(
        private WebhookConfigurationOpenTrigger|null $open = null,
        private WebhookConfigurationClickTrigger|null $click = null,
        private WebhookConfigurationDeliveryTrigger|null $delivery = null,
        private WebhookConfigurationBounceTrigger|null $bounce = null,
        private WebhookConfigurationSpamComplaintTrigger|null $spamComplaint = null,
        private WebhookConfigurationSubscriptionChange|null $subscriptionChange = null,
    ) {
    }

    /** @return array<string, WebhookConfiguration> */
    public function jsonSerialize(): array
    {
        return array_filter([
            'Open' => $this->open,
            'Click' => $this->click,
            'Delivery' => $this->delivery,
            'Bounce' => $this->bounce,
            'SpamComplaint' => $this->spamComplaint,
            'SubscriptionChange' => $this->subscriptionChange,
        ]);
    }

    public function getOpenSettings(): WebhookConfigurationOpenTrigger|null
    {
        return $this->open;
    }

    public function getClickSettings(): WebhookConfigurationClickTrigger|null
    {
        return $this->click;
    }

    public function getDeliverySettings(): WebhookConfigurationDeliveryTrigger|null
    {
        return $this->delivery;
    }

    public function getBounceSettings(): WebhookConfigurationBounceTrigger|null
    {
        return $this->bounce;
    }

    public function getSpamComplaintSettings(): WebhookConfigurationSpamComplaintTrigger|null
    {
        return $this->spamComplaint;
    }

    public function getSubscriptionChangeSettings(): WebhookConfigurationSubscriptionChange|null
    {
        return $this->subscriptionChange;
    }
}
