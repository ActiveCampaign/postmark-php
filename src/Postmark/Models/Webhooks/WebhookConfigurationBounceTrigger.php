<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for Bounce webhooks.
 */
class WebhookConfigurationBounceTrigger implements \JsonSerializable {

    private $enabled;
    private $includeContent;

    /**
     * Create a new WebhookConfigurationBounceTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by Bounce events.
     * @param boolean $includeContent Specifies whether or not the full content of the email bounce is included in webhook POST.
     */
    public function __construct($enabled = false, $includeContent = false) {
        $this->enabled = $enabled;
        $this->includeContent = $includeContent;
    }

    public function jsonSerialize() {
        $retval = array(
            "Enabled" => $this->enabled,
            "IncludeContent" => $this->includeContent
        );

        return $retval;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function getIncludeContent() {
        return $this->includeContent;
    }
}

?>