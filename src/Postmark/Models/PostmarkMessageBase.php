<?php

namespace Postmark\Models;

class PostmarkMessageBase
{
    public string $From;
    public string $To;
    public string $Tag;
    public bool $TrackOpens;
    public string $ReplyTo;
    public string $Cc;
    public string $Bcc;
    public array $Headers;
    public array $Attachments;
    public string $TrackLinks;
    public array $Metadata;
    public string $MessageStream;

    public function __construct(array $values = [])
    {
        $this->From = !empty($values['From']) ? $values['From'] : '';
        $this->To = !empty($values['To']) ? $values['To'] : '';
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : '';
        $this->TrackOpens = !empty($values['TrackOpens']) ? $values['TrackOpens'] : false;
        $this->ReplyTo = !empty($values['ReplyTo']) ? $values['ReplyTo'] : '';
        $this->Cc = !empty($values['Cc']) ? $values['Cc'] : '';
        $this->Bcc = !empty($values['Bcc']) ? $values['Bcc'] : '';
        $this->Headers = !empty($values['Headers']) ? $values['Headers'] : [];
        $this->Attachments = !empty($values['Attachments']) ? $values['Attachments'] : [];
        $this->TrackLinks = !empty($values['TrackLinks']) ? $values['TrackLinks'] : '';
        $this->Metadata = !empty($values['Metadata']) ? $values['Metadata'] : [];
        $this->MessageStream = !empty($values['MessageStream']) ? $values['MessageStream'] : '';
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
     * @return PostmarkMessageBase
     */
    public function setFrom(string $From): PostmarkMessageBase
    {
        $this->From = $From;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->To;
    }

    /**
     * @param string $To
     * @return PostmarkMessageBase
     */
    public function setTo(string $To): PostmarkMessageBase
    {
        $this->To = $To;

        return $this;
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
     * @return PostmarkMessageBase
     */
    public function setTag(string $Tag): PostmarkMessageBase
    {
        $this->Tag = $Tag;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTrackOpens(): bool
    {
        return $this->TrackOpens;
    }

    /**
     * @param bool $TrackOpens
     * @return PostmarkMessageBase
     */
    public function setTrackOpens(bool $TrackOpens): PostmarkMessageBase
    {
        $this->TrackOpens = $TrackOpens;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo(): mixed
    {
        return $this->ReplyTo;
    }

    /**
     * @param string $ReplyTo
     * @return PostmarkMessageBase
     */
    public function setReplyTo(string $ReplyTo): PostmarkMessageBase
    {
        $this->ReplyTo = $ReplyTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getCc(): mixed
    {
        return $this->Cc;
    }

    /**
     * @param string $Cc
     * @return PostmarkMessageBase
     */
    public function setCc(string $Cc): PostmarkMessageBase
    {
        $this->Cc = $Cc;

        return $this;
    }

    /**
     * @return string
     */
    public function getBcc(): mixed
    {
        return $this->Bcc;
    }

    /**
     * @param string $Bcc
     * @return PostmarkMessageBase
     */
    public function setBcc(string $Bcc): PostmarkMessageBase
    {
        $this->Bcc = $Bcc;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): mixed
    {
        return $this->Headers;
    }

    /**
     * @param array $Headers
     * @return PostmarkMessageBase
     */
    public function setHeaders(array $Headers): PostmarkMessageBase
    {
        $this->Headers = $Headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): mixed
    {
        return $this->Attachments;
    }

    /**
     * @param array $Attachments
     * @return PostmarkMessageBase
     */
    public function setAttachments(array $Attachments): PostmarkMessageBase
    {
        $this->Attachments = $Attachments;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackLinks(): mixed
    {
        return $this->TrackLinks;
    }

    /**
     * @param string $TrackLinks
     * @return PostmarkMessageBase
     */
    public function setTrackLinks(string $TrackLinks): PostmarkMessageBase
    {
        $this->TrackLinks = $TrackLinks;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): mixed
    {
        return $this->Metadata;
    }

    /**
     * @param array $Metadata
     * @return PostmarkMessageBase
     */
    public function setMetadata(array $Metadata): PostmarkMessageBase
    {
        $this->Metadata = $Metadata;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageStream(): mixed
    {
        return $this->MessageStream;
    }

    /**
     * @param string $MessageStream
     * @return PostmarkMessageBase
     */
    public function setMessageStream(string $MessageStream): PostmarkMessageBase
    {
        $this->MessageStream = $MessageStream;

        return $this;
    }
}
