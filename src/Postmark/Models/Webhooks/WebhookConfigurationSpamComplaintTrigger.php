<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for SpamComplaint webhooks.
 */
class WebhookConfigurationSpamComplaintTrigger implements \JsonSerializable {

    private $enabled;
    private $includeContent;

    /**
     * Create a new WebhookConfigurationSpamComplaintTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by SpamComplaint events.
     * @param boolean $includeContent Specifies whether or not the full content of the spam complaint is included in webhook POST.
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