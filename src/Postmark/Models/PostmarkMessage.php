<?php

namespace Postmark\Models;

class PostmarkMessage extends PostmarkMessageBase
{
    public string $Subject;
    public string $HtmlBody;
    public string $TextBody;

    public function __construct(array $values = [])
    {
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';

        parent::__construct($values);
    }

    public function getSubject(): string
    {
        return $this->Subject;
    }

    public function setSubject(string $Subject): PostmarkMessage
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->HtmlBody;
    }

    public function setHtmlBody(string $HtmlBody): PostmarkMessage
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    public function getTextBody(): string
    {
        return $this->TextBody;
    }

    public function setTextBody(string $TextBody): PostmarkMessage
    {
        $this->TextBody = $TextBody;

        return $this;
    }
}
