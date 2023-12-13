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

        $arrayType = !empty($values['Details']) ? (array) $values['Details'] : [];
        $this->Details = !empty($values['Details']) ? new PostmarkMessageEventDetails($arrayType) : new PostmarkMessageEventDetails();
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
    public ?string $DeliveryMessage;
    public ?string $DestinationServer;
    public ?string $DestinationIP;
    public ?string $Summary;
    public ?string $BounceID;
    public ?string $Origin;
    public ?string $SuppressSending;
    public ?string $Link;
    public ?string $ClickLocation;

    public function __construct(array $values = [])
    {
        $this->DeliveryMessage = !empty($values['DeliveryMessage']) ? $values['DeliveryMessage'] : '';
        $this->DestinationServer = !empty($values['DestinationServer']) ? $values['DestinationServer'] : '';
        $this->DestinationIP = !empty($values['DestinationIP']) ? $values['DestinationIP'] : '';

        $this->Summary = !empty($values['Summary']) ? $values['Summary'] : '';
        $this->BounceID = !empty($values['BounceID']) ? $values['BounceID'] : '';
        $this->Origin = !empty($values['Origin']) ? $values['Origin'] : '';
        $this->SuppressSending = !empty($values['SuppressSending']) ? $values['SuppressSending'] : '';
        $this->Link = !empty($values['Link']) ? $values['Link'] : '';
        $this->ClickLocation = !empty($values['ClickLocation']) ? $values['ClickLocation'] : '';
    }

    public function getDeliveryMessage(): ?string
    {
        return $this->DeliveryMessage;
    }

    public function setDeliveryMessage(?string $DeliveryMessage): PostmarkMessageEventDetails
    {
        $this->DeliveryMessage = $DeliveryMessage;

        return $this;
    }

    public function getDestinationServer(): ?string
    {
        return $this->DestinationServer;
    }

    public function setDestinationServer(?string $DestinationServer): PostmarkMessageEventDetails
    {
        $this->DestinationServer = $DestinationServer;

        return $this;
    }

    public function getDestinationIP(): ?string
    {
        return $this->DestinationIP;
    }

    public function setDestinationIP(?string $DestinationIP): PostmarkMessageEventDetails
    {
        $this->DestinationIP = $DestinationIP;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->Summary;
    }

    public function setSummary(?string $Summary): PostmarkMessageEventDetails
    {
        $this->Summary = $Summary;
        return $this;
    }

    public function getBounceID(): ?string
    {
        return $this->BounceID;
    }

    public function setBounceID(?string $BounceID): PostmarkMessageEventDetails
    {
        $this->BounceID = $BounceID;
        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->Origin;
    }

    public function setOrigin(?string $Origin): PostmarkMessageEventDetails
    {
        $this->Origin = $Origin;
        return $this;
    }

    public function getSuppressSending(): ?string
    {
        return $this->SuppressSending;
    }

    public function setSuppressSending(?string $SuppressSending): PostmarkMessageEventDetails
    {
        $this->SuppressSending = $SuppressSending;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->Link;
    }

    public function setLink(?string $Link): PostmarkMessageEventDetails
    {
        $this->Link = $Link;
        return $this;
    }

    public function getClickLocation(): ?string
    {
        return $this->ClickLocation;
    }

    public function setClickLocation(?string $ClickLocation): PostmarkMessageEventDetails
    {
        $this->ClickLocation = $ClickLocation;
        return $this;
    }
}

class PostmarkMessageDump
{
    public string $Body;

    public function __construct(string $body = '')
    {
        $this->Body = $body;
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
