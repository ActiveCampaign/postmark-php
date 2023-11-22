<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for SpamComplaint webhooks.
 */
class WebhookConfigurationSpamComplaintTrigger implements \JsonSerializable {

    private $Enabled;
    private $IncludeContent;

    /**
     * Create a new WebhookConfigurationSpamComplaintTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by SpamComplaint events.
     * @param boolean $includeContent Specifies whether or not the full content of the spam complaint is included in webhook POST.
     */
    public function __construct(bool $enabled, bool $includeContent) {
        $this->Enabled = $enabled;
        $this->IncludeContent = $includeContent;
    }

    public function jsonSerialize() {
        $retval = array(
            "Enabled" => $this->Enabled,
            "IncludeContent" => $this->IncludeContent
        );

        return $retval;
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

    public function getIncludeContent(): bool
    {
        return $this->IncludeContent;
    }
}

?>