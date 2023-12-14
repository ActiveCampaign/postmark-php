<?php

namespace Postmark\Models;

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