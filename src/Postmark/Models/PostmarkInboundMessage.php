<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkAddressFull;

class PostmarkInboundMessage
{
    public string $From;
    public PostmarkAddressFull $FromFull;
    public string $FromName;
    public string $To;
    public array $ToFull;
    public string $Cc;
    public array $CcFull;
    public string $Bcc;
    public array $BccFull;
    public string $ReplyTo;
    public string $Subject;
    public string $MessageID;
    public string $OriginalRecipient;
    public string $Date;
    public string $MailboxHash;
    public string $TextBody;
    public string $HtmlBody;
    public string $Tag;
    public string $StrippedTextReply;
    public array $Headers;
    public array $Attachments;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->From = !empty($values['From']) ? $values['From'] : "";
        $this->FromFull = !empty($values['FromFull']) ? $values['FromFull'] : new PostmarkAddressFull(array());
        $this->FromName = !empty($values['FromName']) ? $values['FromName'] : "";
        $this->To = !empty($values['To']) ? $values['To'] : "";
        $this->ToFull = !empty($values['ToFull']) ? $values['ToFull'] : array();
        $this->Cc = !empty($values['Cc']) ? $values['Cc'] : "";
        $this->CcFull = !empty($values['CcFull']) ? $values['CcFull'] : array();
        $this->Bcc = !empty($values['Bcc']) ? $values['Bcc'] : "";
        $this->BccFull = !empty($values['BccFull']) ? $values['BccFull'] : array();
        $this->ReplyTo = !empty($values['ReplyTo']) ? $values['ReplyTo'] : "";
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : "";
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : "";
        $this->OriginalRecipient = !empty($values['OriginalRecipient']) ? $values['OriginalRecipient'] : "";
        $this->Date = !empty($values['Date']) ? $values['Date'] : "";
        $this->MailboxHash = !empty($values['MailboxHash']) ? $values['MailboxHash'] : "";
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : "";
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : "";
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : "";
        $this->StrippedTextReply = !empty($values['StrippedTextReply']) ? $values['StrippedTextReply'] : "";
        $this->Headers = !empty($values['Headers']) ? $values['Headers'] : array();
        $this->Attachments = !empty($values['Attachments']) ? $values['Attachments'] : array();
    }

    /**
     * @return mixed|string
     */
    public function getFrom(): mixed
    {
        return $this->From;
    }

    /**
     * @param mixed|string $From
     * @return PostmarkInboundMessage
     */
    public function setFrom(mixed $From): PostmarkInboundMessage
    {
        $this->From = $From;
        return $this;
    }

    /**
     * @return mixed|\Postmark\Models\PostmarkAddressFull
     */
    public function getFromFull(): mixed
    {
        return $this->FromFull;
    }

    /**
     * @param mixed|\Postmark\Models\PostmarkAddressFull $FromFull
     * @return PostmarkInboundMessage
     */
    public function setFromFull(mixed $FromFull): PostmarkInboundMessage
    {
        $this->FromFull = $FromFull;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getFromName(): mixed
    {
        return $this->FromName;
    }

    /**
     * @param mixed|string $FromName
     * @return PostmarkInboundMessage
     */
    public function setFromName(mixed $FromName): PostmarkInboundMessage
    {
        $this->FromName = $FromName;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTo(): mixed
    {
        return $this->To;
    }

    /**
     * @param mixed|string $To
     * @return PostmarkInboundMessage
     */
    public function setTo(mixed $To): PostmarkInboundMessage
    {
        $this->To = $To;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getToFull(): mixed
    {
        return $this->ToFull;
    }

    /**
     * @param array|mixed $ToFull
     * @return PostmarkInboundMessage
     */
    public function setToFull(mixed $ToFull): PostmarkInboundMessage
    {
        $this->ToFull = $ToFull;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getCc(): mixed
    {
        return $this->Cc;
    }

    /**
     * @param mixed|string $Cc
     * @return PostmarkInboundMessage
     */
    public function setCc(mixed $Cc): PostmarkInboundMessage
    {
        $this->Cc = $Cc;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getCcFull(): mixed
    {
        return $this->CcFull;
    }

    /**
     * @param array|mixed $CcFull
     * @return PostmarkInboundMessage
     */
    public function setCcFull(mixed $CcFull): PostmarkInboundMessage
    {
        $this->CcFull = $CcFull;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getBcc(): mixed
    {
        return $this->Bcc;
    }

    /**
     * @param mixed|string $Bcc
     * @return PostmarkInboundMessage
     */
    public function setBcc(mixed $Bcc): PostmarkInboundMessage
    {
        $this->Bcc = $Bcc;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getBccFull(): mixed
    {
        return $this->BccFull;
    }

    /**
     * @param array|mixed $BccFull
     * @return PostmarkInboundMessage
     */
    public function setBccFull(mixed $BccFull): PostmarkInboundMessage
    {
        $this->BccFull = $BccFull;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getReplyTo(): mixed
    {
        return $this->ReplyTo;
    }

    /**
     * @param mixed|string $ReplyTo
     * @return PostmarkInboundMessage
     */
    public function setReplyTo(mixed $ReplyTo): PostmarkInboundMessage
    {
        $this->ReplyTo = $ReplyTo;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getSubject(): mixed
    {
        return $this->Subject;
    }

    /**
     * @param mixed|string $Subject
     * @return PostmarkInboundMessage
     */
    public function setSubject(mixed $Subject): PostmarkInboundMessage
    {
        $this->Subject = $Subject;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMessageID(): mixed
    {
        return $this->MessageID;
    }

    /**
     * @param mixed|string $MessageID
     * @return PostmarkInboundMessage
     */
    public function setMessageID(mixed $MessageID): PostmarkInboundMessage
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getOriginalRecipient(): mixed
    {
        return $this->OriginalRecipient;
    }

    /**
     * @param mixed|string $OriginalRecipient
     * @return PostmarkInboundMessage
     */
    public function setOriginalRecipient(mixed $OriginalRecipient): PostmarkInboundMessage
    {
        $this->OriginalRecipient = $OriginalRecipient;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDate(): mixed
    {
        return $this->Date;
    }

    /**
     * @param mixed|string $Date
     * @return PostmarkInboundMessage
     */
    public function setDate(mixed $Date): PostmarkInboundMessage
    {
        $this->Date = $Date;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMailboxHash(): mixed
    {
        return $this->MailboxHash;
    }

    /**
     * @param mixed|string $MailboxHash
     * @return PostmarkInboundMessage
     */
    public function setMailboxHash(mixed $MailboxHash): PostmarkInboundMessage
    {
        $this->MailboxHash = $MailboxHash;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTextBody(): mixed
    {
        return $this->TextBody;
    }

    /**
     * @param mixed|string $TextBody
     * @return PostmarkInboundMessage
     */
    public function setTextBody(mixed $TextBody): PostmarkInboundMessage
    {
        $this->TextBody = $TextBody;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getHtmlBody(): mixed
    {
        return $this->HtmlBody;
    }

    /**
     * @param mixed|string $HtmlBody
     * @return PostmarkInboundMessage
     */
    public function setHtmlBody(mixed $HtmlBody): PostmarkInboundMessage
    {
        $this->HtmlBody = $HtmlBody;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTag(): mixed
    {
        return $this->Tag;
    }

    /**
     * @param mixed|string $Tag
     * @return PostmarkInboundMessage
     */
    public function setTag(mixed $Tag): PostmarkInboundMessage
    {
        $this->Tag = $Tag;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getStrippedTextReply(): mixed
    {
        return $this->StrippedTextReply;
    }

    /**
     * @param mixed|string $StrippedTextReply
     * @return PostmarkInboundMessage
     */
    public function setStrippedTextReply(mixed $StrippedTextReply): PostmarkInboundMessage
    {
        $this->StrippedTextReply = $StrippedTextReply;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getHeaders(): mixed
    {
        return $this->Headers;
    }

    /**
     * @param array|mixed $Headers
     * @return PostmarkInboundMessage
     */
    public function setHeaders(mixed $Headers): PostmarkInboundMessage
    {
        $this->Headers = $Headers;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getAttachments(): mixed
    {
        return $this->Attachments;
    }

    /**
     * @param array|mixed $Attachments
     * @return PostmarkInboundMessage
     */
    public function setAttachments(mixed $Attachments): PostmarkInboundMessage
    {
        $this->Attachments = $Attachments;
        return $this;
    }
}