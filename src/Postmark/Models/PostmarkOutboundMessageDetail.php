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
