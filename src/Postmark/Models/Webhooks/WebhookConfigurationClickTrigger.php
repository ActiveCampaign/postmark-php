<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for Click webhooks.
 */
class WebhookConfigurationClickTrigger implements \JsonSerializable {

    private $enabled;

    /**
     * Create a new WebhookConfigurationClickTrigger.
     *
     * @param boolean $enabled Specifies whether the webhooks will be triggered by Click events.
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
