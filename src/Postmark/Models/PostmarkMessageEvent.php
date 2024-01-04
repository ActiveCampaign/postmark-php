<?php

namespace Postmark\Models;

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
        $this->Details = !empty($values['Details']) ? new PostmarkMessageEventDetails(
            $arrayType
        ) : new PostmarkMessageEventDetails();
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
