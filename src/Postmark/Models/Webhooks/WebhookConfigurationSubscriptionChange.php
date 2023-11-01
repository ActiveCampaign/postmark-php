<?php

namespace Postmark\Models\Webhooks;

/**
 * Settings for SubscriptionChange webhooks.
 */
class WebhookConfigurationSubscriptionChange implements \JsonSerializable {

    private $enabled;

    /**
     * Create a new WebhookConfigurationSubscriptionChangeTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by SubscriptionChange events.
     */
    public function __construct($enabled = false) {
        $this->enabled = $enabled;
    }

    public function jsonSerialize(): array
    {
        $retval = array(
            "Enabled" => $this->enabled
        );

        return $retval;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}