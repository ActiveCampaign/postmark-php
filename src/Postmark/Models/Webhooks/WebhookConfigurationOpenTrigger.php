<?php
namespace Postmark\Models\Webhooks;

/**
 * Settings for Open webhooks.
 */
class WebhookConfigurationOpenTrigger implements \JsonSerializable {

    private bool $Enabled;
    private bool $PostFirstOpenOnly;

    /**
     * Create a new WebhookConfigurationOpenTrigger.
     *
     * @param boolean $enabled Specifies whether or not webhooks will be triggered by Open events.
     * @param boolean $postFirstOpenOnly If enabled, Open webhooks will only POST on first open.
     */
    public function __construct(bool $enabled, bool $postFirstOpenOnly) {
        $this->Enabled = $enabled;
        $this->PostFirstOpenOnly = $postFirstOpenOnly;
    }

    public function jsonSerialize(): array
    {
        return array(
            "Enabled" => $this->Enabled,
            "PostFirstOpenOnly" => $this->PostFirstOpenOnly
        );
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

    public function getPostFirstOpenOnly(): bool
    {
        return $this->PostFirstOpenOnly;
    }
}
