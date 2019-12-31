<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for Delivery webhooks.
 */
class WebhookConfigurationDeliveryTrigger implements \JsonSerializable {

    private $enabled;

    /**
     * Create a new WebhookConfigurationDeliveryTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by Delivery events.
     */
    public function __construct($enabled = false) {
        $this->enabled = $enabled;
    }

    public function jsonSerialize() {
        $retval = array(
            "Enabled" => $this->enabled
        );

        return $retval;
    }

    public function getEnabled() {
        return $this->enabled;
    }
}

?>