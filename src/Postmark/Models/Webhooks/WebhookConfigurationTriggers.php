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

    /**
     * Create a new WebhookConfigurationTriggers object.
     *
     * @param WebhookConfigurationOpenTrigger $open Optional settings for Open webhooks.
     * @param WebhookConfigurationClickTrigger $click Optional settings for Click webhooks.
     * @param WebhookConfigurationDeliveryTrigger $delivery Optional settings for Delivery webhooks.
     * @param WebhookConfigurationBounceTrigger $bounce Optional settings for Bounce webhooks.
     * @param WebhookConfigurationSpamComplaintTrigger $spamComplaint Optional settings for SpamComplaint webhooks.
     */
    public function __construct($open = null, $click = null, $delivery = null, $bounce = null, $spamComplaint = null) {
        $this->open = $open;
        $this->click = $click;
        $this->delivery = $delivery;
        $this->bounce = $bounce;
        $this->spamComplaint = $spamComplaint;
    }

    public function jsonSerialize() {
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

        return $retval;
    }

    public function getOpenSettings() {
        return $this->open;
    }

    public function getClickSettings() {
        return $this->click;
    }

    public function getDeliverySettings() {
        return $this->delivery;
    }

    public function getBounceSettings() {
        return $this->bounce;
    }

    public function getSpamComplaintSettings() {
        return $this->spamComplaint;
    }
}

?>