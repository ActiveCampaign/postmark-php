<?php
namespace Postmark\Models\Webhooks;

/**
 * All triggers available for a WebhookConfiguration.
 */
class WebhookConfigurationTriggers implements \JsonSerializable {

    private $open;
    private $click;
    private $delivery;
    private $bounce;
    private $spamComplaint;
    private $subscriptionChange;

    /**
     * Create a new WebhookConfigurationTriggers object.
     *
     * @param WebhookConfigurationOpenTrigger $open Optional settings for Open webhooks.
     * @param WebhookConfigurationClickTrigger $click Optional settings for Click webhooks.
     * @param WebhookConfigurationDeliveryTrigger $delivery Optional settings for Delivery webhooks.
     * @param WebhookConfigurationBounceTrigger $bounce Optional settings for Bounce webhooks.
     * @param WebhookConfigurationSpamComplaintTrigger $spamComplaint Optional settings for SpamComplaint webhooks.
     * @param WebhookConfigurationSubscriptionChange $subscriptionChange Optional settings for SubscriptionChange webhooks.
     */
    public function __construct($open = null, $click = null, $delivery = null, $bounce = null, $spamComplaint = null, $subscriptionChange = null) {
        $this->open = $open;
        $this->click = $click;
        $this->delivery = $delivery;
        $this->bounce = $bounce;
        $this->spamComplaint = $spamComplaint;
        $this->subscriptionChange = $subscriptionChange;
    }

    public function jsonSerialize(): array
    {
        $retval = array();

        if ($this->open !== null) {
            $retval['Open'] = $this->open->jsonSerialize();
        }

        if ($this->click !== null) {
            $retval['Click'] = $this->click->jsonSerialize();
        }

        if ($this->delivery !== null) {
            $retval['Delivery'] = $this->delivery->jsonSerialize();
        }

        if ($this->bounce !== null) {
            $retval['Bounce'] = $this->bounce->jsonSerialize();
        }

        if ($this->spamComplaint !== null) {
            $retval['SpamComplaint'] = $this->spamComplaint->jsonSerialize();
        }

        if ($this->subscriptionChange !== null) {
            $retval['SubscriptionChange'] = $this->subscriptionChange->jsonSerialize();
        }

        return $retval;
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
