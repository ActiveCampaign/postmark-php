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

    private ?WebhookConfigurationOpenTrigger $open;
    private ?WebhookConfigurationClickTrigger $click;
    private ?WebhookConfigurationDeliveryTrigger $delivery;
    private ?WebhookConfigurationBounceTrigger $bounce;
    private ?WebhookConfigurationSpamComplaintTrigger $spamComplaint;
    private ?WebhookConfigurationSubscriptionChange $subscriptionChange;

    /**
     * Create a new WebhookConfigurationTriggers object.
     *
     * @param WebhookConfigurationOpenTrigger|null $open Optional settings for Open webhooks.
     * @param WebhookConfigurationClickTrigger|null $click Optional settings for Click webhooks.
     * @param WebhookConfigurationDeliveryTrigger|null $delivery Optional settings for Delivery webhooks.
     * @param WebhookConfigurationBounceTrigger|null $bounce Optional settings for Bounce webhooks.
     * @param WebhookConfigurationSpamComplaintTrigger|null $spamComplaint Optional settings for SpamComplaint webhooks.
     * @param WebhookConfigurationSubscriptionChange|null $subscriptionChange Optional settings for SubscriptionChange webhooks.
     */
    public function __construct(
        WebhookConfigurationOpenTrigger $open = null,
        WebhookConfigurationClickTrigger $click = null,
        WebhookConfigurationDeliveryTrigger $delivery = null,
        WebhookConfigurationBounceTrigger $bounce = null,
        WebhookConfigurationSpamComplaintTrigger $spamComplaint = null,
        WebhookConfigurationSubscriptionChange $subscriptionChange = null)
    {
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
