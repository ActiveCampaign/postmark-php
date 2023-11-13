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

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : "";
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : "";
        $this->To = !empty($values['To']) ? $values['To'] : array();
        $this->Cc = !empty($values['Cc']) ? $values['Cc'] : array();
        $this->Bcc = !empty($values['Bcc']) ? $values['Bcc'] : array();
        $this->Recipients = !empty($values['Recipients']) ? $values['Recipients'] : array();
        $this->ReceivedAt = !empty($values['ReceivedAt']) ? $values['ReceivedAt'] : "";
        $this->From = !empty($values['From']) ? $values['From'] : "";
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : "";
        $this->Attachments = !empty($values['Attachments']) ? $values['Attachments'] : array();
        $this->Status = !empty($values['Status']) ? $values['Status'] : "";
        !empty($values['Metadata']) ? $this->setMetadata($values['Metadata']) : $this->setMetadata(array());
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->Tag;
    }

    /**
     * @param string $Tag
     * @return PostmarkOutboundMessage
     */
    public function setTag(string $Tag): PostmarkOutboundMessage
    {
        $this->Tag = $Tag;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    /**
     * @param string $MessageID
     * @return PostmarkOutboundMessage
     */
    public function setMessageID(string $MessageID): PostmarkOutboundMessage
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->To;
    }

    /**
     * @param array $To
     * @return PostmarkOutboundMessage
     */
    public function setTo(array $To): PostmarkOutboundMessage
    {
        $this->To = $To;
        return $this;
    }

    /**
     * @return array
     */
    public function getCc(): array
    {
        return $this->Cc;
    }

    /**
     * @param array $Cc
     * @return PostmarkOutboundMessage
     */
    public function setCc(array $Cc): PostmarkOutboundMessage
    {
        $this->Cc = $Cc;
        return $this;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->Bcc;
    }

    /**
     * @param array $Bcc
     * @return PostmarkOutboundMessage
     */
    public function setBcc(array $Bcc): PostmarkOutboundMessage
    {
        $this->Bcc = $Bcc;
        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->Recipients;
    }

    /**
     * @param array $Recipients
     * @return PostmarkOutboundMessage
     */
    public function setRecipients(array $Recipients): PostmarkOutboundMessage
    {
        $this->Recipients = $Recipients;
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
     * @return PostmarkOutboundMessage
     */
    public function setReceivedAt(string $ReceivedAt): PostmarkOutboundMessage
    {
        $this->ReceivedAt = $ReceivedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->From;
    }

    /**
     * @param string $From
     * @return PostmarkOutboundMessage
     */
    public function setFrom(string $From): PostmarkOutboundMessage
    {
        $this->From = $From;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->Subject;
    }

    /**
     * @param string $Subject
     * @return PostmarkOutboundMessage
     */
    public function setSubject(string $Subject): PostmarkOutboundMessage
    {
        $this->Subject = $Subject;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->Attachments;
    }

    /**
     * @param array $Attachments
     * @return PostmarkOutboundMessage
     */
    public function setAttachments(array $Attachments): PostmarkOutboundMessage
    {
        $this->Attachments = $Attachments;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return PostmarkOutboundMessage
     */
    public function setStatus(string $Status): PostmarkOutboundMessage
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->Metadata;
    }

    /**
     * @param mixed $Metadata
     * @return PostmarkOutboundMessage
     */
    public function setMetadata(mixed $Metadata): PostmarkOutboundMessage
    {
        if (is_object($Metadata))
        {
            $this->Metadata = (array)$Metadata;
        }
        else
        {
            $this->Metadata = $Metadata;
        }

        return $this;
    }

}