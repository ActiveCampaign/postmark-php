<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * All triggers available for a WebhookConfiguration.
 */
class WebhookConfigurationTriggers implements JsonSerializable
{
    private ?WebhookConfigurationOpenTrigger $Open;
    private ?WebhookConfigurationClickTrigger $Click;
    private ?WebhookConfigurationDeliveryTrigger $Delivery;
    private ?WebhookConfigurationBounceTrigger $Bounce;
    private ?WebhookConfigurationSpamComplaintTrigger $SpamComplaint;
    private ?WebhookConfigurationSubscriptionChangeTrigger $SubscriptionChange;

    /**
     * Create a new WebhookConfigurationTriggers object.
     *
     * @param null|WebhookConfigurationOpenTrigger               $open               optional settings for Open webhooks
     * @param null|WebhookConfigurationClickTrigger              $click              optional settings for Click webhooks
     * @param null|WebhookConfigurationDeliveryTrigger           $delivery           optional settings for Delivery webhooks
     * @param null|WebhookConfigurationBounceTrigger             $bounce             optional settings for Bounce webhooks
     * @param null|WebhookConfigurationSpamComplaintTrigger      $spamComplaint      optional settings for SpamComplaint webhooks
     * @param null|WebhookConfigurationSubscriptionChangeTrigger $subscriptionChange optional settings for SubscriptionChange webhooks
     */
    public function __construct(
        ?WebhookConfigurationOpenTrigger $open = null,
        ?WebhookConfigurationClickTrigger $click = null,
        ?WebhookConfigurationDeliveryTrigger $delivery = null,
        ?WebhookConfigurationBounceTrigger $bounce = null,
        ?WebhookConfigurationSpamComplaintTrigger $spamComplaint = null,
        ?WebhookConfigurationSubscriptionChangeTrigger $subscriptionChange = null
    ) {
        $this->Open = $open;
        $this->Click = $click;
        $this->Delivery = $delivery;
        $this->Bounce = $bounce;
        $this->SpamComplaint = $spamComplaint;
        $this->SubscriptionChange = $subscriptionChange;
    }

    public function jsonSerialize(): array
    {
        $returnValue = [];

        if (null !== $this->Open) {
            $returnValue['Open'] = $this->Open->jsonSerialize();
        }

        if (null !== $this->Click) {
            $returnValue['Click'] = $this->Click->jsonSerialize();
        }

        if (null !== $this->Delivery) {
            $returnValue['Delivery'] = $this->Delivery->jsonSerialize();
        }

        if (null !== $this->Bounce) {
            $returnValue['Bounce'] = $this->Bounce->jsonSerialize();
        }

        if (null !== $this->SpamComplaint) {
            $returnValue['SpamComplaint'] = $this->SpamComplaint->jsonSerialize();
        }

        if (null !== $this->SubscriptionChange) {
            $returnValue['SubscriptionChange'] = $this->SubscriptionChange->jsonSerialize();
        }

        return $returnValue;
    }

    public function getOpenSettings(): ?WebhookConfigurationOpenTrigger
    {
        return $this->Open;
    }

    public function getClickSettings(): ?WebhookConfigurationClickTrigger
    {
        return $this->Click;
    }

    public function getDeliverySettings(): ?WebhookConfigurationDeliveryTrigger
    {
        return $this->Delivery;
    }

    public function getBounceSettings(): ?WebhookConfigurationBounceTrigger
    {
        return $this->Bounce;
    }

    public function getSpamComplaintSettings(): ?WebhookConfigurationSpamComplaintTrigger
    {
        return $this->SpamComplaint;
    }

    public function getSubscriptionChangeSettings(): ?WebhookConfigurationSubscriptionChangeTrigger
    {
        return $this->SubscriptionChange;
    }
}
