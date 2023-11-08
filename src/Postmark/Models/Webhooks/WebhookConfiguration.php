<?php

namespace Postmark\Models\Webhooks;

use Postmark\Models\Webhooks\HttpAuth as HttpAuth;
use Postmark\Models\Webhooks\HttpHeader as HttpHeader;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChangeTrigger;

class WebhookConfiguration implements \JsonSerializable
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

        if ($numberOfArguments === 1)
        {
            $obj = json_decode(json_encode($arguments[0]));

            //fwrite(STDERR, "aaa-------------------------!!! ". print_r($obj, TRUE));
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
                $local_subscriptionChange);

            $local_id = !empty($obj->ID) ? $obj->ID : 0;
            $local_url = !empty($obj->Url) ? $obj->Url : "";
            $local_message = !empty($obj->MessageStream) ? $obj->MessageStream : "";
            $local_httpauth = $httpAuth;
            $local_httpheaders = !empty($obj->HttpHeaders) ? $obj->HttpHeaders : array();
            $local_triggers = $triggers;

            $this->Build($local_id, $local_url, $local_message, $local_httpauth, $local_httpheaders, $local_triggers);
        }
        else
        {
            $this->Build($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]);
        }
    }

    /**
     * @param int $ID
     * @param string $Url
     * @param string $MessageStream
     * @param \Postmark\Models\Webhooks\HttpAuth $HttpAuth
     * @param array $HttpHeaders
     * @param \Postmark\Models\Webhooks\WebhookConfigurationTriggers $Triggers
     */
    public function Build(
        int $ID = 0,
        string $Url = null,
        string $MessageStream = null,
        ?HttpAuth $HttpAuth = null,
        array $HttpHeaders = array(),
        WebhookConfigurationTriggers $Triggers = null)
    {
        $this->ID = $ID;
        $this->Url = $Url;
        $this->MessageStream = $MessageStream;
        $this->HttpAuth = $HttpAuth;
        $this->HttpHeaders = $HttpHeaders;
        $this->Triggers = $Triggers;
    }

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
    public function getHttpAuth(): HttpAuth
    {
        return $this->HttpAuth;
    }

    /**
     * @param \Postmark\Models\Webhooks\HttpAuth $HttpAuth
     * @return WebhookConfiguration
     */
    public function setHttpAuth(HttpAuth $HttpAuth): WebhookConfiguration
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
    public function getTriggers(): WebhookConfigurationTriggers
    {
        return $this->Triggers;
    }

    /**
     * @param \Postmark\Models\Webhooks\WebhookConfigurationTriggers $Triggers
     * @return WebhookConfiguration
     */
    public function setTriggers(WebhookConfigurationTriggers $Triggers): WebhookConfiguration
    {
        $this->Triggers = $Triggers;
        return $this;
    }

}