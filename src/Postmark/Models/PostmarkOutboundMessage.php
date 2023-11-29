<?php

namespace Postmark\Models;

class PostmarkOutboundMessage
{
    public string $Tag;
    public string $MessageID;
    public array $To;
    public array $Cc;
    public array $Bcc;
    public array $Recipients;
    public string $ReceivedAt;
    public string $From;
    public string $Subject;
    public array $Attachments;
    public string $Status;
    public array $Metadata;

    public function __construct(array $values = [])
    {
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : '';
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
        $this->To = !empty($values['To']) ? $values['To'] : [];
        $this->Cc = !empty($values['Cc']) ? $values['Cc'] : [];
        $this->Bcc = !empty($values['Bcc']) ? $values['Bcc'] : [];
        $this->Recipients = !empty($values['Recipients']) ? $values['Recipients'] : [];
        $this->ReceivedAt = !empty($values['ReceivedAt']) ? $values['ReceivedAt'] : '';
        $this->From = !empty($values['From']) ? $values['From'] : '';
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->Attachments = !empty($values['Attachments']) ? $values['Attachments'] : [];
        $this->Status = !empty($values['Status']) ? $values['Status'] : '';
        !empty($values['Metadata']) ? $this->setMetadata($values['Metadata']) : $this->setMetadata([]);
    }

    public function getTag(): string
    {
        return $this->Tag;
    }

    public function setTag(string $Tag): PostmarkOutboundMessage
    {
        $this->Tag = $Tag;

        return $this;
    }

    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    public function setMessageID(string $MessageID): PostmarkOutboundMessage
    {
        $this->MessageID = $MessageID;

        return $this;
    }

    public function getTo(): array
    {
        return $this->To;
    }

    public function setTo(array $To): PostmarkOutboundMessage
    {
        $this->To = $To;

        return $this;
    }

    public function getCc(): array
    {
        return $this->Cc;
    }

    public function setCc(array $Cc): PostmarkOutboundMessage
    {
        $this->Cc = $Cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->Bcc;
    }

    public function setBcc(array $Bcc): PostmarkOutboundMessage
    {
        $this->Bcc = $Bcc;

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->Recipients;
    }

    public function setRecipients(array $Recipients): PostmarkOutboundMessage
    {
        $this->Recipients = $Recipients;

        return $this;
    }

    public function getReceivedAt(): string
    {
        return $this->ReceivedAt;
    }

    public function setReceivedAt(string $ReceivedAt): PostmarkOutboundMessage
    {
        $this->ReceivedAt = $ReceivedAt;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->From;
    }

    public function setFrom(string $From): PostmarkOutboundMessage
    {
        $this->From = $From;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->Subject;
    }

    public function setSubject(string $Subject): PostmarkOutboundMessage
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getAttachments(): array
    {
        return $this->Attachments;
    }

    public function setAttachments(array $Attachments): PostmarkOutboundMessage
    {
        $this->Attachments = $Attachments;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): PostmarkOutboundMessage
    {
        $this->Status = $Status;

        return $this;
    }

    public function getMetadata(): array
    {
        return $this->Metadata;
    }

    public function setMetadata(mixed $Metadata): PostmarkOutboundMessage
    {
        if (is_object($Metadata)) {
            $this->Metadata = (array) $Metadata;
        } else {
            $this->Metadata = $Metadata;
        }

        return $this;
    }
}
