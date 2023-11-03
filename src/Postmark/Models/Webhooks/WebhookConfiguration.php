<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\HttpAuth as HttpAuth;
use Postmark\Models\Webhooks\HttpHeader as HttpHeader;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;

class WebhookConfiguration implements \JsonSerializable
{
    public int $ID;
    public string $Url;
    public string $MessageStream;
    public HttpAuth $HttpAuth;
    public array $HttpHeaders;
    public WebhookConfigurationTriggers $Triggers;

    public function jsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "Url" => $this->Url,
            "MessageStream" => $this->MessageStream,
            "HttpAuth" => $this->HttpAuth,
            "HttpHeaders" => $this->HttpHeaders
        );
    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return WebhookConfiguration
     */
    public function setID(int $ID): WebhookConfiguration
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->Url;
    }

    /**
     * @param string $Url
     * @return WebhookConfiguration
     */
    public function setUrl(string $Url): WebhookConfiguration
    {
        $this->Url = $Url;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageStream(): string
    {
        return $this->MessageStream;
    }

    /**
     * @param string $MessageStream
     * @return WebhookConfiguration
     */
    public function setMessageStream(string $MessageStream): WebhookConfiguration
    {
        $this->MessageStream = $MessageStream;
        return $this;
    }

    /**
     * @return \Postmark\Models\Webhooks\HttpAuth
     */
    public function getHttpAuth(): \Postmark\Models\Webhooks\HttpAuth
    {
        return $this->HttpAuth;
    }

    /**
     * @param \Postmark\Models\Webhooks\HttpAuth $HttpAuth
     * @return WebhookConfiguration
     */
    public function setHttpAuth(\Postmark\Models\Webhooks\HttpAuth $HttpAuth): WebhookConfiguration
    {
        $this->HttpAuth = $HttpAuth;
        return $this;
    }

    /**
     * @return array
     */
    public function getHttpHeaders(): array
    {
        return $this->HttpHeaders;
    }

    /**
     * @param array $HttpHeaders
     * @return WebhookConfiguration
     */
    public function setHttpHeaders(array $HttpHeaders): WebhookConfiguration
    {
        $this->HttpHeaders = $HttpHeaders;
        return $this;
    }

    /**
     * @return \Postmark\Models\Webhooks\WebhookConfigurationTriggers
     */
    public function getTriggers(): \Postmark\Models\Webhooks\WebhookConfigurationTriggers
    {
        return $this->Triggers;
    }

    /**
     * @param \Postmark\Models\Webhooks\WebhookConfigurationTriggers $Triggers
     * @return WebhookConfiguration
     */
    public function setTriggers(\Postmark\Models\Webhooks\WebhookConfigurationTriggers $Triggers): WebhookConfiguration
    {
        $this->Triggers = $Triggers;
        return $this;
    }

}