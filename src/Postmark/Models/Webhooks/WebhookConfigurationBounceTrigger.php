<?php
namespace Postmark\Models\Webhooks;

class WebhookConfigurationBounceTrigger implements \JsonSerializable {

    private $Enabled;
    private $IncludeContent;

    /**
     * Create a new WebhookConfigurationBounceTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by Bounce events.
     * @param boolean $includeContent Specifies whether or not the full content of the email bounce is included in webhook POST.
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