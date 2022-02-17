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
    private ?WebhookConfigurationOpenTrigger $open;
    private ?WebhookConfigurationClickTrigger $click;
    private ?WebhookConfigurationDeliveryTrigger $delivery;
    private ?WebhookConfigurationBounceTrigger $bounce;
    private ?WebhookConfigurationSpamComplaintTrigger $spamComplaint;
    private ?WebhookConfigurationSubscriptionChange $subscriptionChange;

    /**
     * Create a new WebhookConfigurationTriggers object.
     */
    public function __construct(
        ?WebhookConfigurationOpenTrigger $open = null,
        ?WebhookConfigurationClickTrigger $click = null,
        ?WebhookConfigurationDeliveryTrigger $delivery = null,
        ?WebhookConfigurationBounceTrigger $bounce = null,
        ?WebhookConfigurationSpamComplaintTrigger $spamComplaint = null,
        ?WebhookConfigurationSubscriptionChange $subscriptionChange = null
    ) {
        $this->open = $open;
        $this->click = $click;
        $this->delivery = $delivery;
        $this->bounce = $bounce;
        $this->spamComplaint = $spamComplaint;
        $this->subscriptionChange = $subscriptionChange;
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

    public function getOpenSettings(): ?WebhookConfigurationOpenTrigger
    {
        return $this->open;
    }

    public function getClickSettings(): ?WebhookConfigurationClickTrigger
    {
        return $this->click;
    }

    public function getDeliverySettings(): ?WebhookConfigurationDeliveryTrigger
    {
        return $this->delivery;
    }

    public function getBounceSettings(): ?WebhookConfigurationBounceTrigger
    {
        return $this->bounce;
    }

    public function getSpamComplaintSettings(): ?WebhookConfigurationSpamComplaintTrigger
    {
        return $this->spamComplaint;
    }

    public function getSubscriptionChangeSettings(): ?WebhookConfigurationSubscriptionChange
    {
        return $this->subscriptionChange;
    }
}
