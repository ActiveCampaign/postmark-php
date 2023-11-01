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
     * @param boolean $enabled Specifies whether the webhooks will be triggered by Bounce events.
     * @param boolean $includeContent Specifies whether the full content of the email bounce is included in webhook POST.
     */
    public function __construct($enabled = false, $includeContent = false) {
        $this->enabled = $enabled;
        $this->includeContent = $includeContent;
    }

    public function jsonSerialize(): array
    {
        $retval = array(
            "Enabled" => $this->enabled,
            "IncludeContent" => $this->includeContent
        );

        return $retval;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getIncludeContent(): bool
    {
        return $this->includeContent;
    }
}
