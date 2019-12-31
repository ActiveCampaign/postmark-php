<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for Open webhooks.
 */
class WebhookConfigurationOpenTrigger implements \JsonSerializable {

    private $enabled;
    private $postFirstOpenOnly;

    /**
     * Create a new WebhookConfigurationOpenTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by Open events.
     * @param boolean $postFirstOpenOnly If enabled, Open webhooks will only POST on first open.
     */
    public function __construct($enabled = false, $postFirstOpenOnly = false) {
        $this->enabled = $enabled;
        $this->postFirstOpenOnly = $postFirstOpenOnly;
    }

    public function jsonSerialize() {
        $retval = array(
            "Enabled" => $this->enabled,
            "PostFirstOpenOnly" => $this->postFirstOpenOnly
        );

        return $retval;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function getPostFirstOpenOnly() {
        return $this->postFirstOpenOnly;
    }
}

?>