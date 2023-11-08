<?php

namespace Postmark\Models\Webhooks;

/**
 * Settings for SubscriptionChange webhooks.
 */
class WebhookConfigurationSubscriptionChangeTrigger implements \JsonSerializable {

    private $Enabled;

    /**
     * Create a new WebhookConfigurationSubscriptionChangeTrigger.
     *
     * @param boolean $enabled Specifies whether webhooks will be triggered by SubscriptionChange events.
     */
    public function __construct(bool $enabled) {
        $this->Enabled = $enabled;
    }

    public function jsonSerialize(): array
    {
        $retval = array(
            "Enabled" => $this->Enabled
        );

        return $retval;
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }
}