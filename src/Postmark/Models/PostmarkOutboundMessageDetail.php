<?php

namespace Postmark\Models;

class PostmarkOutboundMessageDetail extends PostmarkOutboundMessage
{
    public string $TextBody;
    public string $HtmlBody;
    public string $Body;
    public PostmarkMessageEvents $MessageEvents;

    public function __construct(array $values)
    {
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->Body = !empty($values['Body']) ? $values['Body'] : '';
        $this->MessageEvents = !empty($values['MessageEvents']) ? new PostmarkMessageEvents($values) : new PostmarkMessageEvents();

        parent::__construct($values);
    }

    public function getTextBody(): string
    {
        return $this->TextBody;
    }

    public function setTextBody(string $TextBody): PostmarkOutboundMessageDetail
    {
        $this->TextBody = $TextBody;

        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->HtmlBody;
    }

    public function setHtmlBody(string $HtmlBody): PostmarkOutboundMessageDetail
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): PostmarkOutboundMessageDetail
    {
        $this->Body = $Body;

        return $this;
    }

    public function getMessageEvents(): PostmarkMessageEvents
    {
        return $this->MessageEvents;
    }

    public function setMessageEvents(PostmarkMessageEvents $MessageEvents): PostmarkOutboundMessageDetail
    {
        $this->MessageEvents = $MessageEvents;

        return $this;
    }
}

class PostmarkMessageEvents
{
    public array $MessageEvents;

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

    public function getMessageEvents(): array
    {
        return $this->MessageEvents;
    }

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
    public PostmarkMessageEventDetails $Details;

    public function __construct(array $values = [])
    {
        $this->Recipient = !empty($values['Recipient']) ? $values['Recipient'] : '';
        $this->Type = !empty($values['Type']) ? $values['Type'] : '';
        $this->ReceivedAt = !empty($values['ReceivedAt']) ? $values['ReceivedAt'] : '';
        $this->Details = !empty($values['Details']) ? new PostmarkMessageEventDetails($values['Details']) : new PostmarkMessageEventDetails();
    }

    public function getRecipient(): string
    {
        return $this->Recipient;
    }

    public function setRecipient(string $Recipient): PostmarkMessageEvent
    {
        $this->Recipient = $Recipient;

        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): PostmarkMessageEvent
    {
        $this->Type = $Type;

        return $this;
    }

    public function getReceivedAt(): string
    {
        return $this->ReceivedAt;
    }

    public function setReceivedAt(string $ReceivedAt): PostmarkMessageEvent
    {
        $this->ReceivedAt = $ReceivedAt;

        return $this;
    }

    public function getDetails(): PostmarkMessageEventDetails
    {
        return $this->Details;
    }

    public function setDetails(PostmarkMessageEventDetails $Details): PostmarkMessageEvent
    {
        $this->Details = $Details;

        return $this;
    }
}

class PostmarkMessageEventDetails
{
    public string $DeliveryMessage;
    public string $DestinationServer;
    public string $DestinationIP;

    public function __construct(array $values = [])
    {
        $this->DeliveryMessage = !empty($values['DeliveryMessage']) ? $values['DeliveryMessage'] : '';
        $this->DestinationServer = !empty($values['DestinationServer']) ? $values['DestinationServer'] : '';
        $this->DestinationIP = !empty($values['DestinationIP']) ? $values['DestinationIP'] : '';
    }

    /**
     * @return string
     */
    public function getDeliveryMessage(): string
    {
        return $this->DeliveryMessage;
    }

    /**
     * @param string $DeliveryMessage
     * @return PostmarkMessageEventDetails
     */
    public function setDeliveryMessage(string $DeliveryMessage): PostmarkMessageEventDetails
    {
        $this->DeliveryMessage = $DeliveryMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationServer(): string
    {
        return $this->DestinationServer;
    }

    /**
     * @param string $DestinationServer
     * @return PostmarkMessageEventDetails
     */
    public function setDestinationServer(string $DestinationServer): PostmarkMessageEventDetails
    {
        $this->DestinationServer = $DestinationServer;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationIP(): string
    {
        return $this->DestinationIP;
    }

    /**
     * @param string $DestinationIP
     * @return PostmarkMessageEventDetails
     */
    public function setDestinationIP(string $DestinationIP): PostmarkMessageEventDetails
    {
        $this->DestinationIP = $DestinationIP;
        return $this;
    }
}

class PostmarkMessageDump
{
    public string $Body;

    public function __construct(array $values = [])
    {
        $this->Body = !empty($values['Body']) ? $values['Body'] : '';
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): PostmarkMessageDump
    {
        $this->Body = $Body;

        return $this;
    }
}
