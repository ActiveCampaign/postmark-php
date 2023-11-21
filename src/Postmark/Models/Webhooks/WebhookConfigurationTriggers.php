<?php
namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChange;

/**
 * All triggers available for a WebhookConfiguration.
 */
class WebhookConfigurationTriggers implements \JsonSerializable {

    private ?WebhookConfigurationOpenTrigger $Open;
    private ?WebhookConfigurationClickTrigger $Click;
    private ?WebhookConfigurationDeliveryTrigger $Delivery;
    private ?WebhookConfigurationBounceTrigger $Bounce;
    private ?WebhookConfigurationSpamComplaintTrigger $SpamComplaint;
    private ?WebhookConfigurationSubscriptionChangeTrigger $SubscriptionChange;

    /**
     * Create a new WebhookConfigurationTriggers object.
     *
     * @param WebhookConfigurationOpenTrigger|null $open Optional settings for Open webhooks.
     * @param WebhookConfigurationClickTrigger|null $click Optional settings for Click webhooks.
     * @param WebhookConfigurationDeliveryTrigger|null $delivery Optional settings for Delivery webhooks.
     * @param WebhookConfigurationBounceTrigger|null $bounce Optional settings for Bounce webhooks.
     * @param WebhookConfigurationSpamComplaintTrigger|null $spamComplaint Optional settings for SpamComplaint webhooks.
     * @param WebhookConfigurationSubscriptionChangeTrigger|null $subscriptionChange Optional settings for SubscriptionChange webhooks.
     */
    public function __construct(
        WebhookConfigurationOpenTrigger $open = null,
        WebhookConfigurationClickTrigger $click = null,
        WebhookConfigurationDeliveryTrigger $delivery = null,
        WebhookConfigurationBounceTrigger $bounce = null,
        WebhookConfigurationSpamComplaintTrigger $spamComplaint = null,
        WebhookConfigurationSubscriptionChangeTrigger $subscriptionChange = null)
    {
        $this->Open = $open;
        $this->Click = $click;
        $this->Delivery = $delivery;
        $this->Bounce = $bounce;
        $this->SpamComplaint = $spamComplaint;
        $this->SubscriptionChange = $subscriptionChange;
    }

    public function jsonSerialize(): array
    {
        $returnValue = array();

        if ($this->Open !== null) {
            $returnValue['Open'] = $this->Open->jsonSerialize();
        }

        if ($this->Click !== null) {
            $returnValue['Click'] = $this->Click->jsonSerialize();
        }

        if ($this->Delivery !== null) {
            $returnValue['Delivery'] = $this->Delivery->jsonSerialize();
        }

        if ($this->Bounce !== null) {
            $returnValue['Bounce'] = $this->Bounce->jsonSerialize();
        }

        if ($this->SpamComplaint !== null) {
            $returnValue['SpamComplaint'] = $this->SpamComplaint->jsonSerialize();
        }

        if ($this->SubscriptionChange !== null) {
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
