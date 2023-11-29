<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkOutboundMessage;

class PostmarkOutboundMessageDetail extends PostmarkOutboundMessage
{
    public string $TextBody;
    public string $HtmlBody;
    public string $Body;
    public PostmarkMessageEvents $MessageEvents;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->Body = !empty($values['Body']) ? $values['Body'] : '';
        $this->MessageEvents = !empty($values['MessageEvents']) ? new PostmarkMessageEvents($values['MessageEvents']) : new PostmarkMessageEvents();

        parent::__construct($values);
    }

    /**
     * @return string
     */
    public function getTextBody(): string
    {
        return $this->TextBody;
    }

    /**
     * @param string $TextBody
     * @return PostmarkOutboundMessageDetail
     */
    public function setTextBody(string $TextBody): PostmarkOutboundMessageDetail
    {
        $this->TextBody = $TextBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody(): string
    {
        return $this->HtmlBody;
    }

    /**
     * @param string $HtmlBody
     * @return PostmarkOutboundMessageDetail
     */
    public function setHtmlBody(string $HtmlBody): PostmarkOutboundMessageDetail
    {
        $this->HtmlBody = $HtmlBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->Body;
    }

    /**
     * @param string $Body
     * @return PostmarkOutboundMessageDetail
     */
    public function setBody(string $Body): PostmarkOutboundMessageDetail
    {
        $this->Body = $Body;
        return $this;
    }

    /**
     * @return PostmarkMessageEvents
     */
    public function getMessageEvents(): PostmarkMessageEvents
    {
        return $this->MessageEvents;
    }

    /**
     * @param PostmarkMessageEvents $MessageEvents
     * @return PostmarkOutboundMessageDetail
     */
    public function setMessageEvents(PostmarkMessageEvents $MessageEvents): PostmarkOutboundMessageDetail
    {
        $this->MessageEvents = $MessageEvents;
        return $this;
    }

}

class PostmarkMessageEvents
{
    public array $MessageEvents;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $tempMessageEvents = [];
        foreach ($values['MessageEvents'] as $messageEvent) {
            $obj = json_decode(json_encode($messageEvent));
            $postmarkMessage = new PostmarkMessageEvent((array) $obj);

            $tempMessageEvents[] = $postmarkMessage;
        }
        $this->MessageEvents = $tempMessageEvents;
    }

    /**
     * @return array
     */
    public function getMessageEvents(): array
    {
        return $this->MessageEvents;
    }

    /**
     * @param array $MessageEvents
     * @return PostmarkMessageEvents
     */
    public function setMessageEvents(array $MessageEvents): PostmarkMessageEvents
    {
        $this->MessageEvents = $MessageEvents;
        return $this;
    }
}

class PostmarkMessageEvent
{
    public string $Recipient;
    public string $Type;
    public string $ReceivedAt;
    public array $Details;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->Recipient = !empty($values['Recipient']) ? $values['Recipient'] : '';
        $this->Type = !empty($values['Type']) ? $values['Type'] : '';
        $this->ReceivedAt = !empty($values['ReceivedAt']) ? $values['ReceivedAt'] : '';
        $this->Details = !empty($values['Details']) ? $values['Details'] : '';
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->Recipient;
    }

    /**
     * @param string $Recipient
     * @return PostmarkMessageEvent
     */
    public function setRecipient(string $Recipient): PostmarkMessageEvent
    {
        $this->Recipient = $Recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return PostmarkMessageEvent
     */
    public function setType(string $Type): PostmarkMessageEvent
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceivedAt(): string
    {
        return $this->ReceivedAt;
    }

    /**
     * @param string $ReceivedAt
     * @return PostmarkMessageEvent
     */
    public function setReceivedAt(string $ReceivedAt): PostmarkMessageEvent
    {
        $this->ReceivedAt = $ReceivedAt;
        return $this;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->Details;
    }

    /**
     * @param array $Details
     * @return PostmarkMessageEvent
     */
    public function setDetails(array $Details): PostmarkMessageEvent
    {
        $this->Details = $Details;
        return $this;
    }
}

class PostmarkMessageDump
{
    public string $Body;

    /**
     * @param string $Body
     */
    public function __construct(string $Body)
    {
        $this->Body = $Body;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->Body;
    }

    /**
     * @param string $Body
     * @return PostmarkMessageDump
     */
    public function setBody(string $Body): PostmarkMessageDump
    {
        $this->Body = $Body;
        return $this;
    }
}
