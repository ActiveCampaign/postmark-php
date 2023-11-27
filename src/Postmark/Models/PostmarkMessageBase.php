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

    public function __construct(array $values)
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
     * @return mixed|string
     */
    public function getFrom(): mixed
    {
        return $this->From;
    }

    /**
     * @param mixed|string $From
     */
    public function setFrom(mixed $From): PostmarkMessageBase
    {
        $this->From = $From;

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
     */
    public function setTo(mixed $To): PostmarkMessageBase
    {
        $this->To = $To;

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
     */
    public function setTag(mixed $Tag): PostmarkMessageBase
    {
        $this->Tag = $Tag;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getTrackOpens(): mixed
    {
        return $this->TrackOpens;
    }

    /**
     * @param bool|mixed $TrackOpens
     */
    public function setTrackOpens(mixed $TrackOpens): PostmarkMessageBase
    {
        $this->TrackOpens = $TrackOpens;

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
     */
    public function setReplyTo(mixed $ReplyTo): PostmarkMessageBase
    {
        $this->ReplyTo = $ReplyTo;

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
     */
    public function setCc(mixed $Cc): PostmarkMessageBase
    {
        $this->Cc = $Cc;

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
     */
    public function setBcc(mixed $Bcc): PostmarkMessageBase
    {
        $this->Bcc = $Bcc;

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
     */
    public function setHeaders(mixed $Headers): PostmarkMessageBase
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
     */
    public function setAttachments(mixed $Attachments): PostmarkMessageBase
    {
        $this->Attachments = $Attachments;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTrackLinks(): mixed
    {
        return $this->TrackLinks;
    }

    /**
     * @param mixed|string $TrackLinks
     */
    public function setTrackLinks(mixed $TrackLinks): PostmarkMessageBase
    {
        $this->TrackLinks = $TrackLinks;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getMetadata(): mixed
    {
        return $this->Metadata;
    }

    /**
     * @param array|mixed $Metadata
     */
    public function setMetadata(mixed $Metadata): PostmarkMessageBase
    {
        $this->Metadata = $Metadata;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMessageStream(): mixed
    {
        return $this->MessageStream;
    }

    /**
     * @param mixed|string $MessageStream
     */
    public function setMessageStream(mixed $MessageStream): PostmarkMessageBase
    {
        $this->MessageStream = $MessageStream;

        return $this;
    }
}
