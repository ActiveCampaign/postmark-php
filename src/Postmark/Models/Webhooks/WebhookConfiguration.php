<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

class WebhookConfiguration implements JsonSerializable
{
    public int $ID;
    public string $Url;
    public string $MessageStream;
    public ?HttpAuth $HttpAuth;
    public array $HttpHeaders;
    public WebhookConfigurationTriggers $Triggers;

    public function __construct()
    {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();

        if (1 === $numberOfArguments) {
            $obj = json_decode(json_encode($arguments[0]));

            $httpAuth = !empty($obj->HttpAuth) ? new HttpAuth($obj->HttpAuth->Username, $obj->HttpAuth->Password) : new HttpAuth();

            $local_triggers = $obj->Triggers;
            $local_open = !empty($local_triggers->Open) ? new WebhookConfigurationOpenTrigger($local_triggers->Open->Enabled, $local_triggers->Open->PostFirstOpenOnly) : null;
            $local_click = !empty($local_triggers->Click) ? new WebhookConfigurationClickTrigger($local_triggers->Click->Enabled) : null;
            $local_delivery = !empty($local_triggers->Delivery) ? new WebhookConfigurationDeliveryTrigger($local_triggers->Delivery->Enabled) : null;
            $local_bounce = !empty($local_triggers->Bounce) ? new WebhookConfigurationBounceTrigger($local_triggers->Bounce->Enabled, $local_triggers->Bounce->IncludeContent) : null;
            $local_spamComplaint = !empty($local_triggers->SpamComplaint) ? new WebhookConfigurationSpamComplaintTrigger($local_triggers->SpamComplaint->Enabled, $local_triggers->SpamComplaint->IncludeContent) : null;
            $local_subscriptionChange = !empty($local_triggers->SubscriptionChange) ? new WebhookConfigurationSubscriptionChangeTrigger($local_triggers->SubscriptionChange->Enabled) : null;

            $triggers = new WebhookConfigurationTriggers(
                $local_open,
                $local_click,
                $local_delivery,
                $local_bounce,
                $local_spamComplaint,
                $local_subscriptionChange
            );

            $local_id = !empty($obj->ID) ? $obj->ID : 0;
            $local_url = !empty($obj->Url) ? $obj->Url : '';
            $local_message = !empty($obj->MessageStream) ? $obj->MessageStream : '';
            $local_httpauth = $httpAuth;
            $local_httpheaders = !empty($obj->HttpHeaders) ? $obj->HttpHeaders : [];
            $local_triggers = $triggers;

            $this->Build($local_id, $local_url, $local_message, $local_httpauth, $local_httpheaders, $local_triggers);
        } else {
            $this->Build($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]);
        }
    }

    public function Build(
        int $ID = 0,
        ?string $Url = null,
        ?string $MessageStream = null,
        ?HttpAuth $HttpAuth = null,
        array $HttpHeaders = [],
        ?WebhookConfigurationTriggers $Triggers = null
    ) {
        $this->ID = $ID;
        $this->Url = $Url;
        $this->MessageStream = $MessageStream;
        $this->HttpAuth = $HttpAuth;
        $this->HttpHeaders = $HttpHeaders;
        $this->Triggers = $Triggers;
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->ID,
            'Url' => $this->Url,
            'MessageStream' => $this->MessageStream,
            'HttpAuth' => $this->HttpAuth,
            'HttpHeaders' => $this->HttpHeaders,
        ];
    }

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): WebhookConfiguration
    {
        $this->ID = $ID;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->Url;
    }

    public function setUrl(string $Url): WebhookConfiguration
    {
        $this->Url = $Url;

        return $this;
    }

    public function getMessageStream(): string
    {
        return $this->MessageStream;
    }

    public function setMessageStream(string $MessageStream): WebhookConfiguration
    {
        $this->MessageStream = $MessageStream;

        return $this;
    }

    public function getHttpAuth(): HttpAuth
    {
        return $this->HttpAuth;
    }

    public function setHttpAuth(HttpAuth $HttpAuth): WebhookConfiguration
    {
        $this->HttpAuth = $HttpAuth;

        return $this;
    }

    public function getHttpHeaders(): array
    {
        return $this->HttpHeaders;
    }

    public function setHttpHeaders(array $HttpHeaders): WebhookConfiguration
    {
        $this->HttpHeaders = $HttpHeaders;

        return $this;
    }

    public function getTriggers(): WebhookConfigurationTriggers
    {
        return $this->Triggers;
    }

    public function setTriggers(WebhookConfigurationTriggers $Triggers): WebhookConfiguration
    {
        $this->Triggers = $Triggers;

        return $this;
    }
}
